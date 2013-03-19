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
	const ARTICLES_TO_KEEP = 50;

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

	public static function switchEnabledStatus($self, $params, $chain)
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
		$stats = array(
			'total_articles' => 0,
			'total_images' => 0,
			'failed_images' => 0,
		);
		$feed = $entity->getFeed();
		$items = $feed->get_items();

		foreach ($items as $item) {
			$count = Articles::find('count', array('conditions' => array(
				'parent_id' => $entity->category_id,
				'guid' => $item->get_id()
			)));
			if (!$count) {
				$article_stats = Articles::addToFeed($category, $item);
				$stats['total_images'] += $article_stats['total_images'];
				$stats['failed_images'] += $article_stats['failed_images'];
				$stats['total_articles'] += 1;
			}
		}

		$cleanup_stats = $entity->cleanup();
		$stats['removed_articles'] = $cleanup_stats['removed_articles'];

		$category->updated = date('Y-m-d H:i:s');
		$category->save();

		return $stats;
	}

	public function cleanup($entity)
	{
		$stats = array(
			'removed_articles' => 0
		);

		$conditions = array(
			'site_id' => $entity->site_id,
			'parent_id' => $entity->category_id
		);

		$count = Articles::find('count', array('conditions' => $conditions));

		if ($count > self::ARTICLES_TO_KEEP) {
			$ids = array_keys(Articles::find('list', array(
				'conditions' => $conditions,
				'limit' => $count - self::ARTICLES_TO_KEEP,
				'order' => array('pubdate' => 'ASC')
			)));

			if ($ids) {
				Articles::remove(array('_id' => $ids));
				$stats['removed_articles'] = count($ids);
			}
		}

		return $stats;
	}

	public function getFeed($entity)
	{
		$feed = new SimplePie();
		$feed->enable_cache(false);
		$feed->set_feed_url($entity->url);
		$feed->init();
		return $feed;
	}
}

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::switchEnabledStatus($self, $params, $chain);
});

Rss::applyFilter('save', function($self, $params, $chain) {
	return Rss::addTimestampsAndType($self, $params, $chain);
});
