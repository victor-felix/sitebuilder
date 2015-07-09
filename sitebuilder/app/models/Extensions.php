<?php
namespace app\models;

use Model;
use Inflector;
use meumobi\sitebuilder\workers\Worker;

class Extensions extends Modules
{
	protected $specification;
	protected $_meta = array(
		'name' => null,
		'title' => null,
		'class' => null,
		'source' => 'extensions',
		'connection' => 'default',
		'initialized' => false,
		'key' => '_id',
		'locked' => false
	);

	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();
		$self->_schema = $parent->_schema + array(
			'extension' => array('type' => 'string', 'null' => false),
			'category_id' => array('type' => 'integer', 'null' => false),
			'enabled'=> array('type' => 'integer', 'default' => 0),
		);
	}

	public function changed($entity, $field) {
		$export = $entity->export();
		if(!$export['exists']) {
			return true;
		}
		if(isset($export['update'][$field])) {
			return $export['data'][$field] != $export['update'][$field];
		} else {
			return false;
		}
	}

	public function hasAttribute($entity, $attr)
	{
		return !is_null($entity->{$attr});
	}

	public function specification($entity, $field)
	{
		if(array_key_exists($field, $this->specification)) {
			return $this->specification[$field];
		}
	}

	public function parent($entity) {
		return Model::load('Categories')->firstById($entity->category_id);
	}

	public static function beforeRemove($extension)
	{
		self::disable($extension);
	}

	public static function category($extension)
	{
		return Model::load('Categories')->firstById($extension->category_id);
	}

	public static function addType($self, $params, $chain)
	{
		$extension = $params['entity'];
		$extension->extension = $extension->specification('type');
		return $chain->next($self, $params, $chain);
	}

	public static function typeFinder($self, $params, $chain)
	{
		$result = $chain->next($self, $params, $chain)->rewind();
		$classname = '\app\models\extensions\\' . Inflector::camelize($result->extension);
		return $classname::find('first', $params['options']);
	}

	public static function available($categoryType, $category_id = false)
	{
		$segment = \MeuMobi::currentSegment();
		$availableExtensions = array();

		if ($segment->extensions) {
			$allExtensions = (array) $segment->extensions;

			//loop all the segment allowed extension types
			foreach ($allExtensions as $extensionName) {
				$classname = '\app\models\extensions\\' . Inflector::camelize($extensionName);
				$extension = $classname::create();
				$allowed = $extension->specification('allowed-items');

				//check if allowed-item is set, if so, check id item type is allowed
				if ($allowed && !in_array($categoryType, $allowed)) continue;

				//if has a category,check a extension for this category exists
				if ($category_id) {
					$item = $classname::find('first', array('conditions' => array(
						'category_id' => $category_id,
						'extension' => $extensionName,
					)));

					if ($item) $extension = $item;
				}

				$availableExtensions[] = $extension;
			}
		}
		return $availableExtensions;
	}

	public static function switchEnabledStatus($self, $params, $chain)
	{
		$extension = $params['entity'];
		if ($extension->enabled) {
			static::enable($extension);
		} else {
			static::disable($extension);
		}
		return $chain->next($self, $params, $chain);
	}

	public static function enable($extension)
	{
		$extension->priority = Worker::PRIORITY_HIGH;
		$category = static::category($extension);
		$category->populate = 'auto';
		$category->save();
	}

	public static function disable($extension)
	{
		$category = static::category($extension);
		$category->removeItems();
		$category->populate = 'manual';
		$category->save();
	}

	public static function removeItems($self, $params, $chain)
	{
		if (isset($params['conditions']['_id'])) {
			$extension = static::find('first', [
				'conditions' => [
					'_id' => $params['conditions']['_id']
				]
			]);

			$category = static::category($extension);
			$category->removeItems();
			$category->populate = 'manual';
			$category->save();
		}

		return $chain->next($self, $params, $chain);
	}
}

Extensions::applyFilter('remove', function($self, $params, $chain) {
	$items = Extensions::find('all', array(
		'conditions' => $params['conditions']
	));

	foreach($items as $item) {
		$classname = '\app\models\extensions\\' . Inflector::camelize($item->extension);
		$classname::beforeRemove($item);
	}

	return $chain->next($self, $params, $chain);
});

Extensions::applyFilter('remove', function($self, $params, $chain) {
	return Extensions::removeItems($self, $params, $chain);
});

Extensions::applyFilter('save', function($self, $params, $chain) {
	return Extensions::addTimestamps($self, $params, $chain);
});

Extensions::applyFilter('save', function($self, $params, $chain) {
	return Extensions::addType($self, $params, $chain);
});

Extensions::finder('type', function($self, $params, $chain) {
	return Extensions::typeFinder($self, $params, $chain);
});
