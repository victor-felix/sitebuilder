<?php
use lithium\data\Connections;
class CreateGeoIndex{
    public static function migrate($connection) {
    	$db = Connections::get('default')->connection;
    	$collection = $db->items;
    	$collection->ensureIndex(array("geo" => "2d"));
    }
}