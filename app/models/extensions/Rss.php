<?php
namespace app\models\extensions;

use lithium\util\Validator;

use app\models\Extensions;

class Rss extends Extensions {
	public $validates = array(
		'url' => array(
			array(
				'validRss',
				'message' => 'The Rss url is invalid'
			)
		)
	);
	
	
	protected $specification = array(
		'title' => 'News feed - RSS',
		'description' => 'Import content automatically from a RSS feed',
		'type' => 'rss'
	);
	
	protected $fields = array(
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
				'url'  => array('type' => 'string', 'default' => ''),
		);
	}
	
	public static function saveRssCategory($self, $params, $chain)
	{
		$extension = $params['entity'];
		if ($extension->category_id ) {
			$category = \Model::load('Categories')->firstById($extension->category_id);
			
			if ($extension->enabled) {
				$category->populate = 'auto';
				$category->feed = $extension->url;
				$category->save();
			} else {
				$category->populate = 'manual';
				$category->feed_url = '';
				$category->save();
			}
		}
		return $chain->next($self, $params, $chain);
	}
}


\lithium\util\Validator::add('validRss', function($value) {
	return (bool)\Model::load('Categories')->isValidRss($value);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::saveRssCategory($self, $params, $chain);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::addTimestampsAndType($self, $params, $chain);
});
