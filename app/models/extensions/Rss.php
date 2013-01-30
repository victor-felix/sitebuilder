<?php

namespace app\models\extensions;

use lithium\util\Validator;
use app\models\Extensions;

class Rss extends Extensions
{
	public $validates = array(
		'url' => array(
			array('validRss', 'message' => 'The Rss url is invalid')
		)
	);

	protected $specification = array(
		'title' => 'News feed - RSS',
		'description' => 'Import content automatically from a RSS feed',
		'type' => 'rss',
		'allowed-items' => array('articles'),
	);

	protected $fields = array(
		'url' => array(
			'title' => 'url of RSS feed',
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

	public static function category($extension)
	{
		return \Model::load('Categories')->firstById($extension->category_id);
	}

	public static function enable($extension)
	{
		$category = self::category($extension);
		$category->populate = 'auto';
		$category->feed_url = $extension->url;
		$category->save();
		$category->updateArticles();
	}

	public static function disable($extension)
	{
		$category = self::category($extension);
		$category->populate = 'manual';
		$category->feed_url = '';
		$category->save();
		$category->removeItems();
	}

	public static function saveRssCategory($self, $params, $chain)
	{
		$extension = $params['entity'];
		if ($extension->category_id) {
			if ($extension->enabled) {
				self::enable($extension);
			} else {
				self::disable($extension);
			}
		}

		return $chain->next($self, $params, $chain);
	}

	public static function beforeRemove($extension)
	{
		self::disable($extension);
	}
}

\lithium\util\Validator::add('validRss', function($value) {
	return (bool) \Model::load('Categories')->checkForValidRss($value);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::saveRssCategory($self, $params, $chain);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::addTimestampsAndType($self, $params, $chain);
});
