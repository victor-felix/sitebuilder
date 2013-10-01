<?php
namespace meumobi\sitebuilder\services;
use lithium\data\Connections;
use app\models\Jobs;
use Exception, GeocodingException, app\models\RecordNotFoundException;

require_once 'lib/geocoding/GoogleGeocoding.php';

class GeocodeItemsService
{
	const PRIORITY_LOW = 0;
	const PRIORITY_HIGH = 1;

	const DELAY_TIME = 250000;
	protected $options;
	protected $logger;
	
	public function __construct(array $options = [])
	{
		$this->options = $options;
	}

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

	protected function logger()
	{
		if ($this->logger) return $this->logger;
	
		if (isset($this->options['logger'])) {
			return $this->logger = $this->options['logger'];
		}
	
		$handler = new \Monolog\Handler\RotatingFileHandler($this->loggerPath());
		$this->logger = new \Monolog\Logger('sitebuilder.geocode', [$handler]);
	
		return $this->logger;
	}
	
	protected function loggerPath()
	{
		return APP_ROOT . '/' . $this->options['logger_path'];
	}

	protected function priorityCriteria()
	{
		$priorities = [
			self::PRIORITY_HIGH => ['$gte' => 1],
			self::PRIORITY_LOW => ['$exists' => false],
		];
	
		return $priorities[$this->options['priority']];
	}
}