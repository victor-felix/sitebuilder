<?php

namespace meumobi\sitebuilder\workers;

use GoogleGeocoding;
use meumobi\sitebuilder\Logger;

class GeocodeWorker extends Worker
{
	const DELAY_TIME = 250000;
	const COMPONENT = 'geocoding';

	public function perform()
	{
		Logger::info(self::COMPONENT, 'start geocoding item', [
			'item id'  => $this->getItem()->_id,
			'address' => $this->getItem()->address,
		]);

		$geocode = GoogleGeocoding::geocode($this->getItem()->address);
		$location = $geocode->results[0]->geometry->location;
		$this->getItem()->geo = array($location->lng, $location->lat);
		$this->getItem()->save();

		Logger::info(self::COMPONENT, 'item successfully geocoded', [
			'item id'  => $this->getItem()->_id,
			'geo' => $this->getItem()->geo,
		]);

		//delay 0.25s to avoid google overquery
		usleep(self::DELAY_TIME);
	}
}
