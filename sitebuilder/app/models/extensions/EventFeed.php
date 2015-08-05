<?php

namespace app\models\extensions;

use app\models\Extensions;

class EventFeed extends Extensions
{
	protected $specification = array(
		'title' => 'Event Feed',
		'description' => 'Import content automatically from a events feed',
		'type' => 'event-feed',
		'allowed-items' => array('events'),
	);

	protected $fields = array(
		'url' => array(
			'title' => 'Feed URL',
			'type' => 'string'
		),
		'import_mode' => array(
			'title' => 'Method of import',
			'type' => 'radio',
			'options' => array('Inclusive', 'Exclusive'),
			'value' => 0,
		),
	);

	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'url' => array('type' => 'string', 'default' => ''),
			'import_mode' => array('type' => 'integer', 'default' => 0),
		);
	}
}

EventFeed::applyFilter('save', function($self, $params, $chain) {
	return EventFeed::switchEnabledStatus($self, $params, $chain);
});

EventFeed::applyFilter('save', function($self, $params, $chain) {
	return EventFeed::addTimestamps($self, $params, $chain);
});

EventFeed::applyFilter('save', function($self, $params, $chain) {
	return EventFeed::addType($self, $params, $chain);
});
