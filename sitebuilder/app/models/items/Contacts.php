<?php

namespace app\models\items;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

use app\models\Items;

class Contacts extends Items
{
	protected $type = 'Contacts';

	protected $fields = array(
		'title' => array(
			'title' => 'Title',
			'type' => 'string'
		),

		'description' => array(
			'title' => 'Description',
			'type' => 'richtext'
		),

		'phone' => array(
			'title' => 'Phone',
			'type' => 'string'
		),

		'address' => array(
			'title' => 'Address',
			'type' => 'string'
		),

	);

	public static function __init()
	{
		parent::__init();

		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'title' => array('type' => 'string', 'default' => ''),
			'description' => array('type' => 'string', 'default' => ''),
			'phone' => array('type' => 'string', 'default' => ''),
			'geo' => array('type' => 'array', 'default' => 0),
			'address' => array('type' => 'string', 'default' => ''),
		);
	}
}

Contacts::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

Contacts::applyFilter('save', function($self, $params, $chain) {
	return Items::addGeocode($self, $params, $chain);
});

Contacts::finder('nearest', function($self, $params, $chain) {
	return Items::nearestFinder($self, $params, $chain);
});

Contacts::finder('within', function($self, $params, $chain) {
	return Items::withinFinder($self, $params, $chain);
});
