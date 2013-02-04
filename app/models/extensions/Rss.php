<?php

namespace app\models\extensions;

require_once 'lib/simplepie/SimplePie.php';

use lithium\util\Validator;
use app\models\Extensions;
use app\models\items\Articles;
use Model;
use SimplePie;

class Rss extends Extensions
{
	protected $specification = array(
		'title' => 'News feed - RSS',
		'description' => 'Import content automatically from a RSS feed',
		'type' => 'rss',
		'allowed-items' => array('articles'),
	);

	protected $fields = array(
		'url' => array(
			'title' => 'URL of the RSS feed',
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
		return Model::load('Categories')->firstById($extension->category_id);
	}

	public static function enable($extension)
	{
		$category = self::category($extension);
		$category->populate = 'auto';
		$category->save();
	}

	public static function disable($extension)
	{
		$category = self::category($extension);
		$category->removeItems();
		$category->populate = 'manual';
		$category->save();
	}

	public static function saveRssCategory($self, $params, $chain)
	{
		$extension = $params['entity'];
		if ($extension->enabled) {
			self::enable($extension);
		} else {
			self::disable($extension);
		}

		return $chain->next($self, $params, $chain);
	}

	public static function beforeRemove($extension)
	{
		self::disable($extension);
	}

	public function updateArticles($entity)
	{
		$category = self::category($entity);
		$feed = $entity->getFeed();
		$items = $feed->get_items();

		foreach ($items as $item) {
			$count = Articles::find('count', array('conditions' => array(
				'parent_id' => $entity->category_id,
				'guid' => $item->get_id()
			)));
			if (!$count) Articles::addToFeed($category, $item);
		}

		$entity->cleanup();

		$category->updated = date('Y-m-d H:i:s');
		$category->save();
	}

	public function cleanup($entity) {
		$conditions = array(
			'site_id' => $entity->site_id,
			'parent_id' => $entity->category_id
		);

		$count = Articles::find('count', array('conditions' => $conditions));

		if ($count > 50) {
			$ids = array_keys(Articles::find('list', array(
				'conditions' => $conditions,
				'limit' => $count - 50,
				'order' => array('pubdate' => 'ASC')
			)));
			if ($ids) Articles::remove(array('_id' => $ids));
		}
	}

	public function getFeed($entity) {
		$feed = new SimplePie();
		$feed->enable_cache(false);
		$feed->set_feed_url($entity->url);
		$feed->init();
		return $feed;
	}
}

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::saveRssCategory($self, $params, $chain);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::addTimestampsAndType($self, $params, $chain);
});
