<?php

use lithium\data\Connections;
use meumobi\sitebuilder\entities\Device;
use meumobi\sitebuilder\repositories\DevicesRepository;

class MigrateDevicesToOwnCollection
{
	public static function migrate($connection)
	{
		$connection = Connections::get('default')->connection;
		$collection = $connection->visitors;
		$visitors = $collection->find();
		$repo = new DevicesRepository();

		foreach ($visitors as $visitor) {
			$devices = isset($visitor['devices']) ? $visitor['devices'] : [];

			foreach ($devices as $d) {
				if (!$d['uuid']) continue;

				$device = new Device([
					'uuid' => $d['uuid'],
					'userId' => $visitor['_id']->{'$id'},
					'siteId' => $visitor['site_id'],
					'pushId' => $d['push_id'],
					'model' => $d['model'],
					'platform' => $d['platform'],
					'platformVersion' => $d['version'],
					'appVersion' => $d['app_version'],
					'appBuild' => $d['app_build'],
				]);

				$repo->create($device);
			}
		}
	}
}
