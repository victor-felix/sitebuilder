<?php

use lithium\data\Connections;

class UpdateAddresses
{
	public static function migrate($connection)
	{
		$connection->query('ALTER TABLE sites ADD COLUMN address TEXT DEFAULT \'\' AFTER timetable');

		$sites = $connection->read(array(
			'table' => 'sites',
			'fields' => 'id, street, number, zip, complement, zone, city, state_id, country_id'
		));

		while ($site = $sites->fetch()) {
			if ($site['street']) {
				$site['state'] = $connection->read(array(
					'table' => 'states',
					'conditions' => array('id' => $site['state_id'])
				))->fetch()['name'];
				$site['country'] = $connection->read(array(
					'table' => 'countries',
					'conditions' => array('id' => $site['country_id'])
				))->fetch()['name'];
				$connection->update(array(
					'table' => 'sites',
					'values' => array(
						'address' => String::insert(":street, :number, :complement\n:zone - :city - :state, :country\n:zip", $site)
					),
					'conditions' => array(
						'id' => $site['id']
					)
				));
			}
		}

		$connection->query('ALTER TABLE sites DROP COLUMN street, DROP COLUMN number, DROP COLUMN city, DROP COLUMN zip, DROP COLUMN complement, DROP COLUMN zone');
	}
}
