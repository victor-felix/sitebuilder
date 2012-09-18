<?php
namespace app\models;
require_once 'lib/utils/Works/Geocode.php';
require_once 'lib/bbcode/Decoda.php';

use Config, Inflector, Model, GoogleGeocoding, Decoda,
	lithium\util\Collection, utils\Geocode as Geocode;

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
		'_id'  => array('type' => 'id'),
		'site_id' => array('type' => 'integer', 'null' => false),
		'parent_id' => array('type' => 'integer', 'null' => false),
		'order' => array('type' => 'integer', 'default' => 0),
		'created'  => array('type' => 'date', 'default' => 0),
		'modified'	=> array('type' => 'date', 'default' => 0),
		'type'	=> array('type' => 'string', 'null' => false),
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

	public static function addTimestamps($self, $params, $chain) {
		$item = $params['entity'];

		if(!$item->id()) {
			$item->created = date('Y-m-d H:i:s');
		}

		$item->modified = date('Y-m-d H:i:s');

		return $chain->next($self, $params, $chain);
	}

	public static function getNotGeocoded($classname, $collection, $conditions = array(), $limit = 20, $page = 1) {
		/** total of items successfully geocoded */
		$count = $classname::find('count', array(
			'conditions' => $conditions + array('geo' => array('$ne' => 0))
		));

		/** calculate last page with geocoded items and prevent division by 0 */
		if($count && $count > $limit) {
			$lastPg = (int) ($count / $limit) + 1;
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
			'conditions' => $conditions + array('geo' => 0),
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

	public static function addGeocode($self, $params, $chain) {
		$item = $params['entity'];

		if(isset($item->latitude) && isset($item->longitude)) {
			$item->geo = array((float) $item->longitude, (float) $item->latitude);
			unset($item->latitude);
			unset($item->longitude);
		} else if($item->changed('address') && !empty($item->address)) {
			if(Geocode::check('geocode')) {
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
			} else {
				try {
					$geocode = GoogleGeocoding::geocode($item->address);
					if($geocode->status == 'OK') {
						$location = $geocode->results[0]->geometry->location;
						$item->geo = array($location->lng, $location->lat);
					}
				}
				catch(\Exception $e) {
					$item->geo = 0;
				}
			}
		} else if(empty($item->address)) {
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
			}/* end foreach loop*/
		}/* end for loop*/
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
