<?php
use lithium\data\Connections;

class TrimGroupNames
{
	public static function migrate($connection)
	{
		$connection = Connections::get('default')->connection;

		$command = '
			return db.%1$s.find({groups:{$exists: true, $not: {$size: 0}}}).forEach(function(item) {
				for (var k in item.groups) {
					item.groups[k] = item.groups[k].trim();
				}
				db.%1$s.save(item);
			});
		';
		$connection->execute(sprintf($command, 'visitors'));
		$connection->execute(sprintf($command, 'items'));
	}
}
