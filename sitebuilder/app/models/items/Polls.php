<?php

namespace app\models\items;

use app\models\Items;

class Polls extends Items {
	protected $type = 'Poll';

	protected $fields = array(
		'title' => [
			'title' => 'Title',
			'type' => 'string',
		],
		'options' => [
			'title' => 'Options',
			'type' => 'multistring',
		],
		'published' => [
			'title' => 'Start Date',
			'type' => 'datetime',
		],
		'end_date' => [
			'title' => 'End Date',
			'type' => 'datetime',
		],
		'groups' => [
			'title' => 'Group',
			'type' => 'groups',
		],
	);

	public static function __init() {
		parent::__init();

		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + [
			'end_date' => ['type' => 'datetime', 'default' => ''],
			'options' => ['type' => 'array', 'default' => []],
		];
	}
}

Events::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});
