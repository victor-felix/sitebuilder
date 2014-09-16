<?php
namespace meumobi\sitebuilder\services;
use lithium\data\Connections;
use app\models\Jobs;
use Exception, GeocodingException, app\models\RecordNotFoundException;

require_once 'lib/geocoding/GoogleGeocoding.php';

class GeocodeItemsService extends Service
{
	const DELAY_TIME = 250000;
	const LOG_CHANNEL  = 'sitebuilder.geocode';

	public function call()
	{
		$this->logger()->info('geocoding items', [
			'priority' => $this->options['priority']
		]);
		$stats = [
			'total_items' => 0,
			'start_time' => microtime(true)
		];
		$connection = Connections::get('default')->connection;
		$jobsCursor = $connection->jobs->find([
			'type' => 'geocode',
			'priority' => $this->priorityCriteria()
		]);
		foreach ($jobsCursor as $job) {
			//wait up to 3 minutes on each interation
			$jobsCursor->timeout(180000);
			$classname = '\app\models\items\\' .
				\Inflector::camelize($job['params']['type']);
			$item = $classname::first(array(
				'conditions' => array(
					'_id' => $job['params']['item_id'],
				),
			));
			if ($item) {
				try {
					$geocode = \GoogleGeocoding::geocode($item->address);
					$location = $geocode->results[0]->geometry->location;
					$item->geo = array($location->lng, $location->lat);
					$item->save();
					$stats['total_items'] += 1;
				} catch (GeocodingException $e) {
					$this->logger->error('geocode error', [
						'exception' => 'GeocodingException',
						'message' => "cant geocode item {$item->_id}",
					]);
				} catch (Exception $e) {
					$this->logger->error('rss update error', [
						'exception' => get_class($e),
						'message' => $e->getMessage(),
						'trace' => $e->getTraceAsString()
					]);
				}
			}
			Jobs::remove(array('_id' => $job['_id']));
			//delay 0.25s to avoid google overquery
			usleep(self::DELAY_TIME);
		}
		$stats['end_time'] = microtime(true);
		$stats['elapsed_time'] = array_unset($stats, 'end_time') -
		array_unset($stats, 'start_time');
		$this->logger()->info('finished geocoding items', $stats);
	}
}
