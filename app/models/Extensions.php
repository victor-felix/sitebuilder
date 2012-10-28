<?php

namespace app\models;

use meumobi\sitebuilder\Extension, Inflector;

class Extensions extends \lithium\data\Model
{
	protected $type = 'extension';
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
		'_id'  => array('type' => 'id'),
		'extension' => array('type' => 'string', 'null' => false),
		'site_id' => array('type' => 'integer', 'null' => false),
		'category_id' => array('type' => 'integer', 'null' => false),
        'created'  => array('type' => 'date', 'default' => 0),
        'modified'  => array('type' => 'date', 'default' => 0),
		'enabled'=> array('type' => 'integer', 'default' => 0),
	);

	public function id($entity)
	{
		if ($entity->_id) {
			return $entity->_id->{'$id'};
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

	public static function addTimestampsAndType($self, $params, $chain)
	{
		$item = $params['entity'];
		$date = date('Y-m-d H:i:s');
		$category = $item->parent();

		if (!$item->id()) {
			$item->created = $date;
		}

		$item->modified = $date;
		$category->modified = $date;
		$category->save();

		$item->extension = $item->specification('type');

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

		if (property_exists($segment, 'extensions')) {
			$allExtensions = (array)$segment->extensions;

			//loop all the segment allowed extension types
			foreach ($allExtensions as $extensionName) {
				$extension = null;
				$classname = '\app\models\extensions\\' . Inflector::camelize($extensionName);
				$extension = $classname::create();

				//check if allowed-item is set, if so, check id item type is allowed
				if ($extension->specification('allowed-items')
					&& !in_array($categoryType, $extension->specification('allowed-items'))) {
					continue;
				}

				//if has a category,check a extension for this category exists
				if ($category_id) {
					$item = $classname::find('first', array('conditions' => array(
						'category_id' => $category_id,
						'extension' => $extensionName,
					)));

					if ($item) {
						$extension = $item;
					}
				}

				$availableExtensions[] = $extension;
			}
		}
		return $availableExtensions;
	}
}

Extensions::applyFilter('save', function($self, $params, $chain) {
	return Extensions::addTimestampsAndType($self, $params, $chain);
});

Extensions::finder('type', function($self, $params, $chain) {
	return Extensions::typeFinder($self, $params, $chain);
});
