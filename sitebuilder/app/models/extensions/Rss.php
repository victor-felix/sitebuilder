<?php
namespace app\models\extensions;

use app\models\Extensions;


class Rss extends Extensions
{
	protected $specification = array(
		'title' => 'News feed - RSS',
		'description' => 'Import content automatically from a news feed',
		'type' => 'rss',
		'allowed-items' => array('articles'),
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
		),
		'use_html_purifier' => array(
			'title' => 'Clean html',
			'type' => 'boolean'
		)
	);

	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'url' => array('type' => 'string', 'default' => ''),
			'use_html_purifier' => array('type' => 'integer', 'default' => 1),
			'import_mode' => array('type' => 'integer', 'default' => 0),
		);
	}
}

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::switchEnabledStatus($self, $params, $chain);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::addType($self, $params, $chain);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::addTimestamps($self, $params, $chain);
});
