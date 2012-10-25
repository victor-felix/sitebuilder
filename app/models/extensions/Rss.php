<?php
namespace app\models\extensions;

use app\models\Extensions;

class Rss extends Extensions {
	
	protected $specification = array(
		'title' => 'News feed - RSS',
		'description' => 'Import content automatically from a RSS feed',
		'type' => 'rss'
	);
	
	protected $fields = array(
			'title' => array(
					'title' => 'Title',
					'type' => 'string'
			),
			'url' => array(
					'title' => 'url of RSS feed',
					'type' => 'string'
			)
	);
	
	public static function __init() {
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();
	
		$self->_schema = $parent->_schema + array(
				'title'  => array('type' => 'string', 'default' => ''),
				'url'  => array('type' => 'string', 'default' => ''),
		);
	}
	
	public static function saveRssCategory($self, $params, $chain)
	{
		$item = $params['entity'];
	/*
		if (!$item->id()) {
			$item->created = date('Y-m-d H:i:s');
		}
	*/
		return $chain->next($self, $params, $chain);
	}
}

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::addTimestampsAndType($self, $params, $chain);
});