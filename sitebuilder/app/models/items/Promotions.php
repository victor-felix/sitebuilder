<?php

namespace app\models\items;

use app\models\Items;

class Promotions extends Items
{
	protected $type = 'Promotions';

	protected $fields = array(
		'title' => array(
			'title' => 'Title',
			'type' => 'string'
		),
		'start' => array(
			'title' => 'Start',
			'type' => 'datetime'
		),
		'end' => array(
			'title' => 'End',
			'type' => 'datetime'
		),
		'link' => array(
			'title' => 'Link',
			'type' => 'string'
		),
		'group' => array(
			'title' => 'Group',
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
			'start' => array('type' => 'datetime', 'default' => ''),
			'end' => array('type' => 'datetime', 'default' => ''),
			'link' => array('type' => 'string', 'default' => ''),
		);
	}
}

Promotions::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

Promotions::finder('nearest', function($self, $params, $chain) {
	return Items::nearestFinder($self, $params, $chain);
});

Promotions::finder('within', function($self, $params, $chain) {
	return Items::withinFinder($self, $params, $chain);
});

Promotions::applyFilter('save', function($self, $params, $chain) {
	return Items::sendPushNotification($self, $params, $chain);
});
