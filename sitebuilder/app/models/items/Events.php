<?php

namespace app\models\items;

use app\models\Items;

class Events extends Items {
	protected $type = 'Event';

	protected $fields = array(
		'title' => array(
			'title' => 'Title',
			'type' => 'string'
		),
		'description' => array(
			'title' => 'Description',
			'type' => 'richtext'
		),
		'address' => array(
			'title' => 'Address',
			'type' => 'string'
		),
		'start_date' => array(
			'title' => 'Start Date',
			'type' => 'datetime'
		),
		'end_date' => array(
			'title' => 'End Date',
			'type' => 'datetime'
		),
		'contact' => array(
			'title' => 'Contact',
			'type' => 'string'
		),
		'group' => array(
			'title' => 'Group',
			'type' => 'string'
		),
	);

	public static function __init() {
		parent::__init();

		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'geo'  => array('type' => 'array', 'default' => 0),
			'description'  => array('type' => 'string', 'default' => ''),
			'address'  => array('type' => 'string', 'default' => ''),
			'contact'  => array('type' => 'string', 'default' => ''),
			'start_date'  => array('type' => 'datetime', 'default' => ''),
			'end_date'  => array('type' => 'datetime', 'default' => '')
		);
	}
}

Events::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

Events::applyFilter('save', function($self, $params, $chain) {
	return Items::addGeocode($self, $params, $chain);
});

Events::finder('nearest', function($self, $params, $chain) {
	return Items::nearestFinder($self, $params, $chain);
});

Events::finder('within', function($self, $params, $chain) {
	return Items::withinFinder($self, $params, $chain);
});
