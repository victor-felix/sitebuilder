<?php
use lithium\data\Connections;
class UpdateNoGeoValue{
    public static function migrate($connection) {
    	$collection = Connections::get('default')->connection->items;
	$newdata = array('$set' => array("geo" => 0));
	$collection->update(array("geo" => array(0,0)), $newdata, array('multiple' => true));
    }
}
