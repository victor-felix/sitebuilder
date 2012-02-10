<?php
use lithium\data\Connections;
class UpdateNoGeoValue{
    public static function migrate($connection) {
    	$collection = Connections::get('default')->connection->items;
		$conditions['$or'][] = array("geo" => array(0,0));
		$conditions['$or'][] = array("geo" => array());
		$newdata = array('$set' => array("geo" => 0));
		$collection->update($conditions , $newdata, array('multiple' => true));
    }
}
