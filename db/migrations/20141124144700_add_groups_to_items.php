<?php
use lithium\data\Connections;

class AddGroupsToItems
{
	public static function migrate($connection)
	{
		$connection = Connections::get('default')->connection;

		$command = "
			return db.items.find().forEach(function(item) {
					item.groups = item.group ? [item.group]: [];    
					db.items.save(item);
			});	
		";
		$connection->execute($command);
	}
}
