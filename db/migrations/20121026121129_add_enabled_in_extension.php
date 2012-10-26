<?php
use lithium\data\Connections;
class AddEnabledInExtension {
    public static function migrate($connection) {
    	$collection = Connections::get('default')->connection->extensions;
		$conditions = array();
		$newdata = array('$set' => array("enabled" => 1));
		$collection->update($conditions , $newdata, array('multiple' => true));
    }
}
