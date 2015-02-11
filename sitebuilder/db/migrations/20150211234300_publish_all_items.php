<?php

use lithium\data\Connections;

class PublishAllItems
{
	public static function migrate($connection)
	{
		$connection = Connections::get('default')->connection;

		$command = '
			return db.items.find().forEach(function(item) {
				item.is_published = true;
				db.items.save(item);
			});
		';
		$connection->execute($command);
	}
}
