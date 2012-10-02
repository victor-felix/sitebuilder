<?php

namespace app\controllers\api;

use lithium\core\Object;

use app\models\Items;
use Model;
use Inflector;

class ItemsController extends ApiController {
	public function index() {
		$conditions = array(
			'site_id' => $this->site()->id
		);
		$order = array('order' => 'ASC');
		if(isset($this->request->query['type'])) {
			$type = $conditions['type'] = $this->request->query['type'];
		}
		else if(isset($this->request->query['category'])) {
			$category_id = $this->request->query['category'];
			$category = Model::load('Categories')->firstById($category_id);
			$conditions['parent_id'] = $category->id;
			$type = $conditions['type'] = $category->type;
			
			//order by news by pubdate
			if ($category->visibility == -1) {
				$order = array('pubdate' => 'DESC');
			}
		}
		
		
		$classname = '\app\models\items\\' . Inflector::camelize($type);
		$items = $classname::find('all', array(
			'conditions' => $conditions,
			'limit' => $this->param('limit', 20),
			'page' => $this->param('page', 1),
			'order' => $order,
		));
		$etag = $this->etag($items);
		$self = $this;

		return $this->whenStale($etag, function() use($type, $items, $self) {
			return $self->toJSON($items);
		});
	}

	protected function _prepareAdd($data) {
		$need		= array('type','parent_id');
		$discard	= array('created','updated','geo');

		foreach ($discard as $field){
			if(array_key_exists($field, $data))
				unset($data[$field]);
		}
		foreach ($need as $field){
			if(!array_key_exists($field,$data))
				throw new \Exception('need more params: '.$field);
		}
		return $data;
	}

	/**
	 * Add new item related to another item
	 * @return array|multitype:NULL
	 */

	public function add() {
		try{
			$data = $this->_prepareAdd( $this->request->data );

			$images = isset($data['images']) ? $data['images']: false;

			$item = Items::find('first', array('conditions' => array(
					'_id' => $this->request->params['id'],
					'site_id' => $this->site()->id
			)));

			if(!$item){
				throw new \Exception('invalid item');
			}
			$classname = '\app\models\items\\' . Inflector::camelize($data['type']);

			$newItem = $classname::create();
			$newItem->set($data);
			$newItem->site_id = $this->site()->id;

			/** if not saved stop right here */
			if(!$newItem->save()){
				$this->response->status(422);
				return;
			}

			/** add to related and save */
			if($item->related instanceof \lithium\core\Object){
				$related = $item->related->to('array');
				$related[] =  $newItem->id();

			} else {
				$related[] = $newItem->id();
			}

			$item->related = $related;
			$item->save();

			/** if images, update and save*/
			if($images){
				foreach($images as	$id => $image) {
					if(!is_numeric($id)) continue;
					$record = Model::load('Images')->firstById($id);
					if(!$record)continue;
					$record->title			= $image['title'];
					$record->foreign_key	= $newItem->id();
					$record->save();
				}
			}

			$this->response->status(201);
			return $this->toJSON($newItem);

		} catch (\Exception $e){
			$this->response->status(422);
		}
	}

	public function related() {
		$item = Items::find('first', array('conditions' => array(
			'_id' => $this->request->params['id'],
			'site_id' => $this->site()->id
		)));

		if($item->related) {
			$classname = '\app\models\items\\' . Inflector::camelize($item->type);
			$related = $classname::find('all', array(
				'conditions' => array(
					'_id' => $item->related->to('array'),
					'site_id' => $this->site()->id
				),
				'limit' => $this->param('limit', 20),
				'page' => $this->param('page', 1)
			));
		}else{
			$related = array();
		}

		$etag = $this->etag($related);
		$self = $this;

		return $this->whenStale($etag, function() use($related, $self) {
			return $self->toJSON($related);
		});
	}

	public function search() {
		$category_id = $this->request->params['category_id'];
		$category = Model::load('Categories')->firstById($category_id);
		$keyword = "/{$this->request->query['keyword']}/iu";
		$conditions = array(
			'site_id' => $this->site()->id,
			'parent_id' => $category->id,
			'title' => array('like' => $keyword)
		);

		$classname = '\app\models\items\\' . Inflector::camelize($category->type);
		$items = $classname::find('all', array(
			'conditions' => $conditions,
			'limit' => $this->param('limit', 20),
			'page' => $this->param('page', 1)
		));

		return $this->toJSON($items);
	}

	public function show() {
		$item = Items::find('type', array('conditions' => array(
			'_id' => $this->request->params['id'],
			'site_id' => $this->site()->id
		)));

		$etag = $this->etag($item);
		$self = $this;

		return $this->whenStale($etag, function() use($item, $self) {
			return $item->toJSON();
		});
	}

	public function by_category() {
		$categories = Model::load('Categories')->allBySiteIdAndVisibility($this->site()->id, 1);
		$items = array();

		$etag = '';
		foreach($categories as $category) {
			$current_items = $category->childrenItems($this->param('limit', 20));
			$items[$category->id] = $current_items->to('array');
			$etag .= $this->etag($current_items);
		}

		$self = $this;

		return $this->whenStale($etag, function() use($items, $self) {
			return $items;
		});
	}

	public function create() {
		$category_id = $this->request->data['parent_id'];
		$category = Model::load('Categories')->firstById($category_id);
		$classname = '\app\models\items\\' . Inflector::camelize($category->type);
		$item = $classname::create();
		$item->set($this->request->data);
		$item->site_id = $this->site()->id;
		$item->type = $category->type;

		if($item->save()) {
			$this->response->status(201);
			return $item->toJSON();
		}
		else {
			$this->response->status(422);
		}
	}

	public function update() {
		$item = Items::find('first', array('conditions' => array(
			'_id' => $this->request->params['id'],
			'site_id' => $this->site()->id
		)));

		$item->set(array(
			'site_id' => $this->site()->id
		) + $this->request->data);

		if($item->save()) {
			$this->response->status(200);
			return $item->toJSON();
		}
		else {
			$this->response->status(422);
		}
	}

	public function destroy() {
		Items::remove(array('_id' => $this->request->params['id']));
		$this->response->status(200);
	}
}
