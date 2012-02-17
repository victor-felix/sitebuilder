<?php
require_once 'lib/geocoding/GoogleGeocoding.php';
require_once 'lib/utils/Jobs.php';

class Geocode extends Jobs
{
	protected $lockFile = 'geocodejob.lock';
	protected $debug = true;
	const GEOCODE_LIMIT = 20;

	protected function process($entity)
	{
		$total = self::count( array(
				'conditions' => array(
					'type' => 'geocode'
				)
			) );
		$pages = ceil($total / static::GEOCODE_LIMIT);
		$successfulJobs = array();
		for ($page = 1; $page <= $pages; $page++) {
			$jobs = self::all( array(
				'conditions' => array('type' => 'geocode'),
				'limit' => static::GEOCODE_LIMIT,
				'page' => $page,
				'order' => 'modified',
			) );
			if (!$jobs) {
				continue;
			}
			foreach ($jobs as $job) {
				$result = $job->addGeocode();
				if ($result == 'OK') {
					$successfulJobs[] = (string)$job->_id;
				} else if ($result == 'LIMIT') {
					break 2; 
				}
			}/* end foreach */
		}/* end for */
		self::remove( array('_id' => $successfulJobs) );
		return parent::process($entity);
	}

	public function addGeocode($entity) 
	{
		/* if cant geocode, item go to the end of list */
		$error = function($job) use ($entity) {
			echo 'error: ' . $entity->_id, "\n";
			$job->modified = date('Y-m-d H:i:s');
			$job->save($entity);  
		};

		$classname = '\app\models\items\\' . Inflector::camelize($entity->params->type);
		$item = $classname::first(array('conditions' => array(
					'_id' => $entity->params->item_id,
				)));

		/* if item don't exists remove job */
		if (!$item) {
			echo 'no item:' . $entity->_id,"\n";
			return 'OK';
		}

		$geocode = GoogleGeocoding::geocode($item->address);

		if (!$geocode) {
			$error($this);
			return;
		}

		switch ($geocode->status) {
			case 'OVER_QUERY_LIMIT':
				echo 'LIMIT',"\n";
				return 'LIMIT';
				break;
			case 'OK':
				$location = $geocode->results[0]->geometry->location;
					$item->geo = array($location->lng, $location->lat);
				if ($item->save()) {
					return 'OK';
				}
				break;
			default:
				return 'OK';
		}
	}
}
Geocode::applyFilter('save', function($self, $params, $chain) {
	return Geocode::beforeSave($self, $params, $chain);
});
