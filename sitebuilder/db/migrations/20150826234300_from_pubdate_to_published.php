<?php

use lithium\data\Connections;

class FromPubdateToPublished
{
	public static function migrate($connection)
	{
		$connection = Connections::get('default')->connection;

		$command = '
			db.items.find({pubdate: {$exists: true, $ne: 0}})
			.forEach(function(item) {
				item.published = item.pubdate;
				db.items.save(item);
			});
		';
		$connection->execute($command);
	}
}
