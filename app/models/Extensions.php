<?php

namespace app\models;

use Model;
use Inflector;

class Extensions extends \lithium\data\Model
{
	protected $specification;
	protected $fields;

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

	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'extension' => array('type' => 'string', 'null' => false),
		'site_id' => array('type' => 'integer', 'null' => false),
		'category_id' => array('type' => 'integer', 'null' => false),
		'created' => array('type' => 'date', 'default' => 0),
		'modified' => array('type' => 'date', 'default' => 0),
		'enabled'=> array('type' => 'integer', 'default' => 0),
	);

	public function id($entity)
	{
		if ($entity->_id) {
			return $entity->_id->{'$id'};
		}
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

	public function fields($entity)
	{
		return array_keys($this->fields);
	}

	public function field($entity, $field)
	{
		if(array_key_exists($field, $this->fields)) {
			return (object) $this->fields[$field];
		}
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

	public static function category($extension)
	{
		return Model::load('Categories')->firstById($extension->category_id);
	}

	public static function addTimestampsAndType($self, $params, $chain)
	{
		$extension = $params['entity'];
		$date = date('Y-m-d H:i:s');
		$category = $extension->parent();

		if (!$extension->id()) {
			$extension->created = $date;
		}

		$extension->modified = $date;
		$category->modified = $date;
		$category->save();

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

	public static function beforeRemove($extension) {

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

Extensions::applyFilter('save', function($self, $params, $chain) {
	return Extensions::addTimestampsAndType($self, $params, $chain);
});

Extensions::finder('type', function($self, $params, $chain) {
	return Extensions::typeFinder($self, $params, $chain);
});
