<?php

namespace app\models\extensions;

use app\models\Extensions;

class StoreLocator extends Extensions
{
	protected $specification = array(
		'title' => 'Store Locator',
		'description' => 'A map interface for address listing',
		'type' => 'store-locator',
		'allowed-items' => array('business', 'stores', 'events'),
	);

	protected $fields = array(
		'language' => array(
			'title' => 'Default language',
			'type' => 'select',
			'options' => array('en' => 'English', 'pt' => 'Português'),
		),
		'itemLimit' => array(
			'title' => 'Limit of items per page',
			'type' => 'string'
		)
	);

	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'language'  => array('type' => 'string', 'default' => 'en'),
			'itemLimit'  => array('type' => 'integer', 'default' => 10),
		);
	}
}
StoreLocator::applyFilter('save', function($self, $params, $chain) {
	return StoreLocator::addTimestamps($self, $params, $chain);
});

StoreLocator::applyFilter('save', function($self, $params, $chain) {
	return StoreLocator::addType($self, $params, $chain);
});
