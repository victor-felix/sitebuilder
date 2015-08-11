<?php

namespace app\models\extensions;

use app\models\Extensions;
use meumobi\sitebuilder\services\BulkImportItems;

class EventFeed extends Extensions
{
	protected $specification = [
		'title' => 'Event Feed',
		'description' => 'Import content automatically from a events feed',
		'type' => 'event-feed',
		'allowed-items' => ['events'],
	];

	protected $fields = [
		'url' => [
			'title' => 'Feed URL',
			'type' => 'string',
		],
		'import_mode' => [
			'title' => 'Method of import',
			'type' => 'radio',
			'options' => [
				BulkImportItems::INCLUSIVE_IMPORT => 'Inclusive',
				BulkImportItems::EXCLUSIVE_IMPORT => 'Exclusive',
			],
		],
	];

	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + [
			'url' => ['type' => 'string', 'default' => ''],
			'import_mode' => ['type' => 'string', 'default' => BulkImportItems::INCLUSIVE_IMPORT],
		];
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
