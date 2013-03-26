<?php

namespace app\models;

require_once 'lib/utils/Works/Geocode.php';
require_once 'lib/bbcode/Decoda.php';

use lithium\data\Connections;
use Config;
use Inflector;
use Model;
use GoogleGeocoding;
use Decoda;
use lithium\util\Collection;
use utils\Geocode;

Collection::formats('lithium\net\http\Media');

class Items extends \lithium\data\Model {
	const EXPORT_LIMIT = 500;
	protected $getters = array();
	protected $setters = array();
	protected $_meta = array(
		'name' => null,
		'title' => null,
		'class' => null,
		'source' => 'items',
		'connection' => 'default',
		'initialized' => false,
		'key' => '_id',
		'locked' => false
	);

	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'site_id' => array('type' => 'integer', 'null' => false),
		'parent_id' => array('type' => 'integer', 'null' => false),
		'order' => array('type' => 'integer', 'default' => 0),
		'created' => array('type' => 'date', 'default' => 0),
		'modified' => array('type' => 'date', 'default' => 0),
		'type' => array('type' => 'string', 'null' => false),
		'title' => array('type' => 'string', 'null' => false)
	);

	public function breadcrumbs($entity, $category_id) {
		return Model::load('Categories')->firstById($category_id)->bredcrumbs();
	}

	public function images($entity) {
		return Model::load('Images')->allByRecord('Items', $this->id($entity));
	}

	public function image($entity) {
		return Model::load('Images')->firstByRecord('Items', $this->id($entity));
	}

	public function id($entity) {
		if($entity->_id) {
			return $entity->_id->{'$id'};
		}
	}

	public function imageModel() {
		return 'Items';
	}

	public function parent($entity) {
		return Model::load('Categories')->firstById($entity->parent_id);
	}

	public function resizes() {
		$config = Config::read('BusinessItems.resizes');
		if(is_null($config)) {
			$config = array();
		}

		return $config;
	}

	public function fields($entity) {
		return array_keys($this->fields);
	}

	public function field($entity, $field) {
		if(array_key_exists($field, $this->fields)) {
			return (object) $this->fields[$field];
		}
		else {
			return null;
		}
	}

	public function type($entity) {
		return $this->type;
	}

	public function hasAttribute($entity, $attr) {
		return !is_null($entity->{$attr});
	}

	public function hasGetter($entity, $attr) {
		return in_array($attr, $this->getters);
	}

	public function hasSetter($entity, $attr) {
		return in_array($attr, $this->setters);
	}

	public function toJSON($entity) {
		$self = $entity->to('array');

		foreach ($this->fields as $code => $field) {
			if ($field['type'] == 'richtext'
				&& !(isset($self['format']) && $self['format'] == 'html')) {
				$allowedTagsConvertion = array('b', 'i', 'color', 'url', 'big', 'small');
				$parser = new Decoda($self[$code], $allowedTagsConvertion);
				$self[$code] = '<p>' . $parser->parse(true) . '</p>';
			}
		}

		$self['images'] = array();
		$images = $this->images($entity);
		foreach($images as $image) {
			$self['images'] []= $image->toJSON();
		}

		return $self;
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

	public function getFirst($entity) {
		return self::find('first', array(
			'conditions' => array(
				'parent_id' => $entity->parent_id,
			),
			'order' => array('order' => 'ASC')
		));
	}

	public function getLast($entity) {
		return self::find('first', array(
				'conditions' => array(
						'parent_id' => $entity->parent_id,
				),
				'order' => array('order' => 'DESC')
		));
	}

	public function moveUp($entity, $steps = 1) {
		$oldOrder = $entity->order;
		$previus = $this->findByOrder($entity, $oldOrder - $steps);

		if (!$previus) {
			return false;
		}

		$entity->order = $previus->order;
		$previus->order = $oldOrder;
		if ($entity->save() && $previus->save()) {
			return $entity->order;
		}
	}

	public function moveDown($entity, $steps = 1) {
		$oldOrder = $entity->order;
		$previus = $this->findByOrder($entity, $oldOrder + $steps);

		if (!$previus) {
			return false;
		}

		$entity->order = $previus->order;
		$previus->order = $oldOrder;
		if ($entity->save() && $previus->save()) {
			return $entity->order;
		}
	}

	public function findByOrder($entity, $order) {
		if (!(int)$order) {
			return false;
		}
		//'order' => array('created' => 'DESC')
		$item = Items::find('first', array('conditions' => array(
				'order' => $order,
				'parent_id' => $entity->parent_id,
		)));

		if (!$item) {
			$conditions = array(
					'parent_id' => $entity->parent_id,
			);

			if ($order > 0) {
				$conditions['order'] = array('>' => $order);
			} else {
				$conditions['order'] = array('<' => $order);
			}

			$item = Items::find('first', compact('conditions'));
		}
		return $item;
	}

	/**
	 * set the order value with the last order plus 1
	 */
	public static function addOrder($self, $params, $chain) {
		$item = $params['entity'];

		if(!$item->id()) {
			$last = $item->getLast();
			if ($last) {
				$item->order = $last->order + 1;
			} else {
				$item->order = 1;
			}
		}

		return $chain->next($self, $params, $chain);
	}

	public static function resetItemsOrdering($parentId)
	{
		$orderMethod = "
			function orderItems(parent) {
				try	{
					var order = 0;
			  		db.items.find({parent_id: parent}).forEach( function(item) {
			                     	item.order = ++order;
			                     	db.items.save(item);
			                     });
			        return order;
			    } catch(err) {
				  return 0;
				}
			}
		";

		$db = Items::connection()->connection;
		$response = $db->execute($orderMethod, (array)$parentId);

		if (isset($response['retval'])) {
			return $response['retval'];
		}
	}

	/**
	 * Update item ordering after remove item
	 */
	public static function updateOrdering($self, $params, $chain) {
		if(isset($params['conditions']['_id'])) {
			$id = $params['conditions']['_id'];
			$item = static::find('first', array(
				'conditions' => array(
					'_id' => $id
				),
				'fields' => array(
					'order',
					'parent_id',
					'site_id'
				)
			));

			if ($item) {
				$conditions = array(
					'order' => array('>' => $item->order),
					'parent_id' => $item->parent_id,
					'site_id' => $item->site_id,
				);
				$values = array(
						'$inc' => array('order' => -1),
						);

				static::update($values, $conditions);
			}

		}
		return $chain->next($self, $params, $chain);
	}

	public static function removeImages($self, $params, $chain)
	{
		if (isset($params['conditions']['_id'])) {
			$model = Model::load('Images');
			$images = $model->allByRecord('Items', $params['conditions']['_id']);
			foreach ($images as $item) {
				$model->delete($item->id);
			}
		}
		return $chain->next($self, $params, $chain);
	}

	public static function addTimestamps($self, $params, $chain)
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

		return $chain->next($self, $params, $chain);
	}

	public static function getNotGeocoded($self, $collection, $params) {
		$limit = $params['limit'];
		$page = $params['page'];
		$conditions = $params['conditions'];

		if (!$limit || !$page || $collection->count() >= $limit) {
			return $collection;
		}
		$conditions['geo'] = array('$size' => 2);
		// total of items successfully geocoded
		$count = $self::find('count', array(
			'conditions' => $conditions
		));

		// calculate last page with geocoded items and prevent division by 0
		if($count > $limit) {
			$lastPg = ($count / $limit) + 1;
			$lastPg = floor($lastPg);
		} else {
			$lastPg = 1;
		}

		// current page of not geocoded items
		$currPg = $page - $lastPg;
		$rest = ($limit * $lastPg) - $count;

		if($currPg) {
			$offset = ($limit * ($currPg - 1)) + $rest;
		}
		else {
			$offset = 0;
			$limit = $rest;
		}

		$conditions['geo'] = 0;
		$itemsLost = $self::find('all', array(
			'conditions' => $conditions,
			'limit' => $limit,
			'offset' => $offset
		));

		if(!$collection->count()) {
			return $itemsLost;
		}

		/** add items to existing collection */
		while($item = $itemsLost->next()) {
			$collection->append($item);
		}

		return $collection;
	}

	public static function addGeocode($self, $params, $chain) 
	{
		$item = $params['entity'];
		if(isset($item->latitude) && isset($item->longitude)) {
			$item->geo = array((float) $item->longitude, (float) $item->latitude);
			unset($item->latitude);
			unset($item->longitude);
		} else if($item->changed('address') && !empty($item->address)) {
			$result = $chain->next($self, $params, $chain);
			$job = \app\models\Jobs::create();
			$data = array(
				'type' => 'geocode',
				'params' => array(
					'item_id' => (string) $item->_id,
					'type' => $item->type,
				),
			);
			$job->set($data);
			$job->save();
			return $result;
		} else if(empty($item->address)) {
			$item->geo = 0;
		}

		return $chain->next($self, $params, $chain);
	}

	public static function typeFinder($self, $params, $chain)
	{
		$result = $chain->next($self, $params, $chain)->rewind();

		if ($result) {
			$classname = '\app\models\items\\' . Inflector::camelize($result->type);
			return $classname::find('first', $params['options']);
		} else {
			throw new \app\models\RecordNotFoundException('item not found');
		}
	}

	public static function nearestFinder($self, $params, $chain) {
		$lat = (float) array_unset($params['options']['conditions'], 'lat');
		$lng = (float) array_unset($params['options']['conditions'], 'lng');

		$params['options']['conditions']['geo'] = array(
			'$near' => array($lng, $lat),
		);

		$result = $chain->next($self, $params, $chain);
		return static::getNotGeocoded($self, $result, $params['options']);
	}

	public static function withinFinder($self, $params, $chain) {
		$ne_lat = (float) array_unset($params['options']['conditions'], 'ne_lat');
		$ne_lng = (float) array_unset($params['options']['conditions'], 'ne_lng');
		$sw_lat = (float) array_unset($params['options']['conditions'], 'sw_lat');
		$sw_lng = (float) array_unset($params['options']['conditions'], 'sw_lng');

		$lower_left = array($sw_lng, $sw_lat);
		$upper_right = array($ne_lng, $ne_lat);

		$params['options']['conditions']['geo'] = array(
			'$within' => array('$box' => array($lower_left, $upper_right))
		);

		return $chain->next($self, $params, $chain);
	}

	public static function paginate($params = array()) {
		$defaults = array(
			'limit' => 10,
			'page'	=> 1,
			'conditions' => array(),
		);

		$params = array_merge($defaults, $params);
		$total = static::count(array('conditions' => $params['conditions']));
		$pages = $params['limit'] ? ceil($total / $params['limit']) : 1;

		if(($params['limit'] * $params['page']) > $total){
			$params['page'] = $pages;
		}

		$items = static::find('all', $params);

		if(!$items){
			return array();
		}

		$paginate = (object)$params;
		$paginate->total = $total;
		$paginate->pages = $pages;
		$paginate->class = get_called_class();
		return compact('items', 'paginate');
	}

	public static function exportTo($format = 'csv', $conditions = array()) {
		$total = static::count( array('conditions' => $conditions) );
		$pages = ceil($total / static::EXPORT_LIMIT);
		/** get fields that can be exported */
		$fields = array_filter(static::create()->fields(), function($field) {
			return !is_array($field['type']);
		});

		$toCsv = function($item) {
			echo '"' . implode('","', $item) . '"' . "\n";
			flush();
		};

		$toCsv(array_merge(array('id','type'), $fields));

		/** parse all items by chunks */
		for($page = 1; $page <= $pages; $page++) {
			$items = static::all( array(
					'conditions' => $conditions,
					'limit' => static::EXPORT_LIMIT,
					'page' => $page,
			) );

			if(!$items) {
				continue;
			}

			foreach ($items as $item) {
				$itemArray = array(
						'id' => $item->id(),
						'type' => $item->type
				);

				foreach($fields as $field) {
					$value = $item[$field];
					if ($value) {
						if ($value instanceof \lithium\core\Object) {
							$itemArray[$field] = implode(',', $value->to('array'));
						} else {
							$itemArray[$field] = $value;
						}
					} else {
						$itemArray[$field] = '';
					}
				}
				if($format == 'csv') {
					$toCsv($itemArray);
				}
			}
		}
	}
}

Items::applyFilter('remove', function($self, $params, $chain) {
	if(isset($params['conditions']['_id'])) {
		$id = $params['conditions']['_id'];
		$items = Items::find('all', array(
			'conditions' => array('related' => $id)
		));
		foreach($items as $item) {
			$index = array_search($id, $item->related->to('array'));
			unset($item->related[$index]);
			$item->related = $item->related->to('array');
			$item->save();
		}
	}
	return $chain->next($self, $params, $chain);
});

Items::applyFilter('remove', function($self, $params, $chain) {
	return Items::updateOrdering($self, $params, $chain);
});

Items::applyFilter('remove', function($self, $params, $chain) {
	return Items::removeImages($self, $params, $chain);
});

Items::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

Items::applyFilter('save', function($self, $params, $chain) {
	return Items::addOrder($self, $params, $chain);
});

Items::finder('type', function($self, $params, $chain) {
	return Items::typeFinder($self, $params, $chain);
});
