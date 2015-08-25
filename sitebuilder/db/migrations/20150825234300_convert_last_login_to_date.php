<?php

use lithium\data\Connections;

class ConvertLastLoginToDate
{
	public static function migrate($connection)
	{
		$connection = Connections::get('default')->connection;

		$command = 'return db.visitors.find().forEach(function(visitor) {
			if (visitor.last_login) {
				visitor.last_login = new Date(visitor.last_login);
				db.visitors.save(visitor);
			}
		});';

		$connection->execute($command);
	}
}
