<?php

namespace meumobi\sitebuilder\workers;

use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;//TODO move exceptions for a more generic namespace

class GeocodeWorker extends Worker
{
	const DELAY_TIME = 250000;

	public function perform()
	{
		$this->logger()->info('start geocoding item', [
			'item id'  => $this->getItem()->_id,
			'address' => $this->getItem()->address,
		]);
		$geocode = \GoogleGeocoding::geocode($this->getItem()->address);
		$location = $geocode->results[0]->geometry->location;
		$this->getItem()->geo = array($location->lng, $location->lat);
		$this->getItem()->save();
		$this->logger()->info('item successfully geocoded', [
			'item id'  => $this->getItem()->_id,
			'geo' => $this->getItem()->geo,
		]);
		//delay 0.25s to avoid google overquery
		usleep(self::DELAY_TIME);
	}
}
