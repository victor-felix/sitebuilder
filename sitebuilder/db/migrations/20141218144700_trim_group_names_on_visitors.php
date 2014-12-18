<?php
use lithium\data\Connections;

class TrimGroupNamesOnVisitors
{
	public static function migrate($connection)
	{
		$connection = Connections::get('default')->connection;

		$command = '
			return db.visitors.find({groups:{$exists: true, $not: {$size: 0}}}).forEach(function(item) {
				for (var k in item.groups) {
					item.groups[k] = item.groups[k].trim();
				}
				db.visitors.save(item);
			});
		';
		$connection->execute($command);
	}
}
