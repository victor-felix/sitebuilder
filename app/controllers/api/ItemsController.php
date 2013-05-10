<?php

namespace app\controllers\api;

use lithium\core\Object;

use app\models\Items;
use app\models\RecordNotFoundException;
use Model;
use Inflector;

class ItemsController extends ApiController {
	public function index()
	{
		$conditions = array('site_id' => $this->site()->id);
		$order = array('order' => 'ASC');

		if (!isset($this->request->query['category'])) {
			throw new InvalidArgumentException('category id required');
		}

		$category_id = $this->request->query['category'];
		$category = Model::load('Categories')->firstById($category_id);
		$conditions['parent_id'] = $category_id;
		$type = $conditions['type'] = $category->type;

		$classname = '\app\models\items\\' . Inflector::camelize($type);
		$items = $classname::find('all', array(
			'conditions' => $conditions,
			'limit' => $this->param('limit', 20),
			'page' => $this->param('page', 1),
			'order' => $order,
		));

		return $this->toJSON($items);
	}

	public function promotions()
	{
		$category_id = $this->request->params['category_id'];
		$category = Model::load('Categories')->firstByIdAndType($category_id, 'promotions');

		if (!$category) {
			throw new RecordNotFoundException('category does not have promotions');
		}

		$date = $this->request->get('query:time') ?: time();

		$conditions = array(
			'site_id' => $this->site()->id,
			'parent_id' => $category_id,
			'type' => 'promotions',
			'start' => array('$lt' => $date),
			'end' => array('$gt' => $date),
		);

		$items = \app\models\items\Promotions::find('all', array(
			'conditions' => $conditions,
			'limit' => $this->param('limit', 20),
			'page' => $this->param('page', 1),
			'order' => array('order' => 'ASC'),
		));

		return $this->toJSON($items);
	}

	protected function _prepareAdd($data)
	{
		$need = array('type','parent_id');
		$discard = array('created','updated','geo');

		foreach ($discard as $field) {
			if (array_key_exists($field, $data)) unset($data[$field]);
		}

		foreach ($need as $field) {
			if (!array_key_exists($field,$data)) throw new \Exception('need more params: '. $field);
		}

		return $data;
	}

	public function add()
	{
		$this->requireUserAuth();

		try {
			$data = $this->_prepareAdd($this->request->data);

			$images = isset($data['images']) ? $data['images']: false;

			$item = Items::find('first', array('conditions' => array(
				'_id' => $this->request->params['id'],
				'site_id' => $this->site()->id
			)));

			if (!$item) throw new \Exception('invalid item');

			$classname = '\app\models\items\\' . Inflector::camelize($data['type']);
			$newItem = $classname::create();
			$newItem->set($data);
			$newItem->site_id = $this->site()->id;

			if (!$newItem->save()) {
				$this->response->status(422);
				return;
			}

			if ($item->related instanceof \lithium\core\Object) {
				$related = $item->related->to('array');
				$related []= $newItem->id();
			} else {
				$related []= $newItem->id();
			}

			$item->related = $related;
			$item->save();

			if ($images) {
				foreach ($images as $id => $image) {
					if (!is_numeric($id)) continue;
					$record = Model::load('Images')->firstById($id);
					$record->title = $image['title'];
					$record->foreign_key = $newItem->id();
					$record->save();
				}
			}

			$this->response->status(201);
			return $this->toJSON($newItem);
		} catch (\Exception $e) {
			$this->response->status(422);
		}
	}

	public function related()
	{
		$item = Items::find('first', array('conditions' => array(
			'_id' => $this->request->params['id'],
			'site_id' => $this->site()->id
		)));

		if (!$item) throw new \app\models\RecordNotFoundException('item not found');

		if ($item->related) {
			$classname = '\app\models\items\\' . Inflector::camelize($item->type);
			$related = $classname::find('all', array(
				'conditions' => array(
					'_id' => $item->related->to('array'),
					'site_id' => $this->site()->id
				),
				'limit' => $this->param('limit', 20),
				'page' => $this->param('page', 1)
			));
		} else {
			$related = array();
		}

		return $this->toJSON($related);
	}

	public function search()
	{
		$params = $this->request->query;
		$conditions = $this->postConditions($params, array(
			'title' => 'like',
			'description' => 'like'
		));
		$conditions['site_id'] = $this->site()->id;

		$items = Items::find('all',array(
			'conditions' => $conditions,
			'limit' => $this->param('limit', 20),
			'page' => $this->param('page', 1)
		))->to('array');

		return array_reduce($items, function($items, $item) {
			$classname = '\app\models\items\\' . Inflector::camelize($item['type']);
			$items[$item['type']] []= $classname::create($item)->toJSON();
			return $items;
		}, array());
	}

	public function show()
	{
		$item = Items::find('type', array('conditions' => array(
			'_id' => $this->request->params['id'],
			'site_id' => $this->site()->id
		)));

		return $item->toJSON();
	}

	public function latest()
	{
		$conditions = array('site_id' => $this->site()->id);

		if ($this->param('parent_id')) $conditions['parent_id'] = $this->param('parent_id');

		$items = Items::find('all', array(
			'conditions' => $conditions,
			'order' => array('created' => 'DESC'),
			'limit' => $this->param('limit', 20),
			'page' => $this->param('page', 1),
		))->to('array');

		return array_map(function($item) {
			$classname = '\app\models\items\\' . Inflector::camelize($item['type']);
			return $classname::create($item)->toJSON();
		}, $items);
	}

	public function by_category()
	{
		$categories = Model::load('Categories')->allBySiteIdAndVisibility($this->site()->id, 1);
		$items = array();

		foreach($categories as $category) {
			$current_items = $category->childrenItems($this->param('limit', 20));
			$items[$category->id] = $current_items->to('array');
		}

		return $items;
	}

	public function create()
	{
		$this->requireUserAuth();

		$category_id = $this->request->data['parent_id'];
		$category = Model::load('Categories')->firstById($category_id);
		$classname = '\app\models\items\\' . Inflector::camelize($category->type);
		$item = $classname::create();
		$item->set($this->request->data);
		$item->site_id = $this->site()->id;
		$item->type = $category->type;

		if ($item->save()) {
			$this->response->status(201);
			return $item->toJSON();
		} else {
			$this->response->status(422);
		}
	}

	public function update()
	{
		$this->requireUserAuth();

		$item = Items::find('first', array('conditions' => array(
			'_id' => $this->request->params['id'],
			'site_id' => $this->site()->id
		)));

		if (!$item) throw new \app\models\RecordNotFoundException('item not found');

		$item->set(array(
			'site_id' => $this->site()->id
		) + $this->request->data);

		if ($item->save()) {
			$this->response->status(200);
			return $item->toJSON();
		} else {
			$this->response->status(422);
		}
	}

	public function destroy()
	{
		$this->requireUserAuth();

		Items::remove(array('_id' => $this->request->params['id']));
		$this->response->status(200);
	}
}
