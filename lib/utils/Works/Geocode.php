<?php

namespace utils;

use GeocodingException;
use app\models\RecordNotFoundException;

require_once 'lib/utils/Work.php';
require_once 'lib/geocoding/GoogleGeocoding.php';

class Geocode extends Work
{
	const DELAY_TIME = 250000;

	public function init() {}

	public function run()
	{
		while ($job = $this->getJob()) {
			$classname = '\app\models\items\\' .
				\Inflector::camelize($job->params->type);
			$item = $classname::first(array(
				'conditions' => array(
					'_id' => $job->params->item_id,
				),
			));

			if (!$item) {
				$job->delete();
				continue;
			}

			try {
				$geocode = \GoogleGeocoding::geocode($item->address);
				$location = $geocode->results[0]->geometry->location;
				$item->geo = array($location->lng, $location->lat);
				$item->save();
				$this->log->logInfo("Geocode work: item {$item->_id} geocoded");
			} catch (GeocodingException $e) {
				$this->log->logError("cant geocode item {$item->_id}");
			} catch (RecordNotFoundException $e) {
				$this->log->logError('geocode error: %s', $e->getMessage());
			}

			$job->delete();
			usleep(self::DELAY_TIME);
		}
	}

	protected function getJob()
	{
		return \app\models\Jobs::first(array(
			'conditions' => array('type' => 'geocode'),
			'order' => 'modified'
		));
	}
}
