<?php

namespace app\models\extensions;

require_once 'lib/simplepie/SimplePie.php';

use lithium\util\Validator;
use app\models\Extensions;
use app\models\items\Articles;
use app\models\RecordNotFoundException;
use Model;
use SimplePie;
use Exception;

class Rss extends Extensions
{
	const ARTICLES_TO_KEEP = 50;
	const PRIORITY_HIGH = 2;
	const PRIORITY_MEDIUM = 1;
	const PRIORITY_LOW = 0;

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

	public static function beforeRemove($extension)
	{
		self::disable($extension);
	}

	public function updateArticles($entity)
	{
		try {
			$category = self::category($entity);
		} catch (RecordNotFoundException $e) {
			$extensionData = $entity->to('array');
			Rss::remove(array('_id' => $entity->_id));
			throw new Exception('Invalid extension removed from database: ' . print_r($extensionData, 1));
		}
		$stats = array(
			'total_articles' => 0,
			'total_images' => 0,
			'failed_images' => 0,
		);

		try {
			$feed = $entity->getFeed();
			$items =  array_reverse($feed->get_items());
			$classname = '\app\models\items\\' . \Inflector::camelize($category->type);
			foreach ($items as $item) {
				$count = $classname::find('count', array('conditions' => array(
					'parent_id' => $entity->category_id,
					'guid' => $item->get_id()
				)));
				if (!$count) {
					$article_stats = $classname::addToFeed($category, $item);
					$stats['total_images'] += $article_stats['total_images'];
					$stats['failed_images'] += $article_stats['failed_images'];
					$stats['total_articles'] += 1;
				}
			}
		} catch(Exception $e) {
			// do nothing if the feed fails for any reason
		}

		$cleanup_stats = $entity->cleanup();
		$stats['removed_articles'] = $cleanup_stats['removed_articles'];

		$category->updated = date('Y-m-d H:i:s');
		$category->save();

		$entity->priority = $entity->priority % 2;

		if (!$entity->priority) {
			unset($entity->priority);
		}

		$entity->save();

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
