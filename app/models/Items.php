<?php

namespace app\models;

use Config;
use Inflector;
use Model;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

use lithium\util\Collection;
Collection::formats('lithium\net\http\Media');

class Items extends \lithium\data\Model {
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
		'_id'  => array('type' => 'id'),
		'site_id' => array('type' => 'integer', 'null' => false),
		'parent_id' => array('type' => 'integer', 'null' => false),
		'order' => array('type' => 'integer', 'default' => 0),
		'created'  => array('type' => 'date', 'default' => 0),
		'modified'  => array('type' => 'date', 'default' => 0),
		'type'  => array('type' => 'string', 'null' => false),
		'title'  => array('type' => 'string', 'null' => false)
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

		$self['images'] = array();
		$images = $this->images($entity);
		foreach($images as $image) {
			$self['images'] []= $image->toJSON();
		}

		return $self;
	}

	public static function addTimestamps($self, $params, $chain) {
		$item = $params['entity'];

		if(!$item->id) {
			$item->created = date('Y-m-d H:i:s');
		}

		$item->modified = date('Y-m-d H:i:s');

		return $chain->next($self, $params, $chain);
	}

	public static function getNotGeocoded($classname, $collection, $conditions = array(), $limit = 20, $page = 1 ){
		/** total of items successfully geocoded */
		$count = $classname::find('count', array(
			'conditions' => $conditions + array('geo' => array('$ne' => 0))
		));

		/** calculate last page with geocoded items and prevent division by 0 */
		if($count && $count > $limit) {
			$lastPg = (int)($count/$limit) + 1;
		}
		else {
			$lastPg = 1;
		}

		/** current page of not geocoded items */
		$currPg = $page - $lastPg;
		$rest = ($limit * $lastPg) - $count;

		if($currPg) {
			$offset = ($limit * ($currPg - 1)) + $rest;
		}
		else {
			$offset = 0;
			$limit = $rest;
		}

		$itemsLost = $classname::find('all', array(
				'conditions' 	=> $conditions + array('geo'=>0),
				'limit' 		=> $limit,
				'offset'		=> $offset
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

	public static function addGeocode($self, $params, $chain) {
		$item = $params['entity'];
		$do_geocode = true;

		if($item->_id) {
			$do_geocode = !(bool) self::find('count', array(
				'conditions' => array(
					'_id' => $item->_id,
					'address' => $item->address,
					'geo'	=> 0
				)
			));
		}

		if($do_geocode && !empty($item->address)) {
			try {
				$geocode = GoogleGeocoding::geocode($item->address);
				$location = $geocode->results[0]->geometry->location;
				$item->geo = array($location->lng, $location->lat);
			}
			catch(\Exception $e) {
				$item->geo = 0;
			}
		}
		else {
			$item->geo = 0;
		}

		return $chain->next($self, $params, $chain);
	}

	public static function typeFinder($self, $params, $chain) {
		$result = $chain->next($self, $params, $chain)->rewind();
		$classname = '\app\models\items\\' . Inflector::camelize($result->type);

		return $classname::find('first', $params['options']);
	}

	public static function nearestFinder($self, $params, $chain) {
		$EARTH = 6378; // both in km
		$DISTANCE = 10;

		$lat = (float) array_unset($params['options']['conditions'], 'lat');
		$lng = (float) array_unset($params['options']['conditions'], 'lng');
		$geo = array(
			'$near' => array($lng, $lat),
			//'$nearSphere' => array($lng, $lat),
			//'$maxDistance' => $DISTANCE / $EARTH
		);

		$params['options']['conditions']['geo'] = $geo;

		return $chain->next($self, $params, $chain);
	}

	public static function withinFinder($self, $params, $chain) {
		$ne_lat = (float) array_unset($params['options']['conditions'], 'ne_lat');
		$ne_lng = (float) array_unset($params['options']['conditions'], 'ne_lng');
		$sw_lat = (float) array_unset($params['options']['conditions'], 'sw_lat');
		$sw_lng = (float) array_unset($params['options']['conditions'], 'sw_lng');

		$lower_left = array($sw_lng, $sw_lat);
		$upper_right = array($ne_lng, $ne_lat);

		$geo = array(
			'$within' => array('$box' => array($lower_left, $upper_right))
		);

		$params['options']['conditions']['geo'] = $geo;

		return $chain->next($self, $params, $chain);
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

Items::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

Items::finder('type', function($self, $params, $chain) {
	return Items::typeFinder($self, $params, $chain);
});
