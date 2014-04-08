<?php

namespace app\models\extensions;

use app\models\Extensions;

class EventFeed extends Extensions
{
	const PRIORITY_HIGH = 2;
	const PRIORITY_MEDIUM = 1;
	const PRIORITY_LOW = 0;

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
		)
	);

	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'url' => array('type' => 'string', 'default' => ''),
		);
	}
}

EventFeed::applyFilter('save', function($self, $params, $chain) {
	return EventFeed::switchEnabledStatus($self, $params, $chain);
});

EventFeed::applyFilter('save', function($self, $params, $chain) {
	return EventFeed::addTimestampsAndType($self, $params, $chain);
});
