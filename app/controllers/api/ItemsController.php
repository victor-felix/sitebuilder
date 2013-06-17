<?php

namespace app\controllers\api;

use lithium\core\Object;

use app\models\Items;
use app\models\RecordNotFoundException;
use Model;
use Inflector;

class ItemsController extends ApiController {
	const PAGE_LIMIT = 20;

	public function index()
	{
		$category_id = $this->request->get('params:category_id');
		$category = Model::load('Categories')->firstById($category_id);

		$params = [
			'order' => ['order' => 'ASC'],
			'conditions' => [
				'site_id' => $this->site()->id,
				'parent_id' => $category->id,
				'type' => $category->type
			],
			'limit' => self::PAGE_LIMIT,
			'page' => $this->param('page', 1)
		];

		$url = "/api/{$this->site()->domain}/categories/{$category->id}/items";
		$url_params = ['category' => $category_id];

		return $this->paginate($params, $url, $url_params);
	}

	public function promotions()
	{
		$category_id = $this->request->get('params:category_id');
		$date = $this->request->get('query:time') ?: time();

		$category = Model::load('Categories')->firstByIdAndType($category_id, 'promotions');

		if (!$category) {
			throw new RecordNotFoundException('category does not have promotions');
		}

		$params = [
			'order' => ['order' => 'ASC'],
			'conditions' => [
				'site_id' => $this->site()->id,
				'parent_id' => $category->id,
				'type' => $category->type,
				'start' => ['$lt' => $date],
				'end' => ['$gt' => $date]
			],
			'limit' => self::PAGE_LIMIT,
			'page' => $this->param('page', 1)
		];

		$url = "/api/{$this->site()->domain}/categories/{$category->id}/promotions";
		$url_params = ['time' => $date];

		return $this->paginate($params, $url, $url_params);
	}

	public function add()
	{
		$this->requireUserAuth();

		try {
			$data = $this->prepareAdd($this->request->data);

			$images = isset($data['images']) ? $data['images'] : false;

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
		$item = Items::find('first', [
			'conditions' => [
				'_id' => $this->request->get('params:id'),
				'site_id' => $this->site()->id
			]
		]);

		if (!$item) {
			throw new \app\models\RecordNotFoundException('item not found');
		}

		if (!$item->related) {
			return ['items' => array()];
		}

		$params = [
			'conditions' => [
				'_id' => $item->related->to('array'),
				'site_id' => $this->site()->id
			],
			'limit' => self::PAGE_LIMIT,
			'page' => $this->param('page', 1)
		];

		$url = "/api/{$this->site()->domain}/items/{$item->id()}/related";
		$url_params = [];

		return $this->paginate($params, $url, $url_params);
	}

	public function search()
	{
		$conditions = $this->postConditions($this->request->query, [
			'title' => 'like',
			'description' => 'like'
		]);
		$conditions['site_id'] = $this->site()->id;

		$params = [
			'conditions' => $conditions,
			'limit' => self::PAGE_LIMIT,
			'page' => $this->param('page', 1)
		];

		$url = "/api/{$this->site()->domain}/items/search";
		$url_params = $this->request->query;

		return $this->paginate($params, $url, $url_params, function($items, $item) {
			$classname = '\app\models\items\\' . Inflector::camelize($item['type']);
			$items[$item['type']] []= $classname::create($item)->toJSON();
			return $items;
		});
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
		$parent_id = $this->request->get('params:parent_id');

		$params = [
			'conditions' => ['site_id' => $this->site()->id],
			'order' => ['created' => 'DESC'],
			'limit' => self::PAGE_LIMIT,
			'page' => $this->param('page', 1)
		];

		if ($parent_id) {
			$params['conditions']['parent_id'] = $parent_id;
		}

		$url = "/api/{$this->site()->domain}/items/latest";
		$url_params = ['parent_id' => $parent_id];

		return $this->paginate($params, $url, $url_params);
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

	public function news()
	{
		$category = $this->site()->newsCategory();

		$params = [
			'order' => ['pubdate' => 'DESC'],
			'conditions' => [
				'site_id' => $this->site()->id,
				'parent_id' => $category->id
			],
			'limit' => self::PAGE_LIMIT,
			'page' => $this->param('page', 1)
		];

		$url = "/api/{$this->site()->domain}/news";
		$url_params = [];

		return $this->paginate($params, $url, $url_params);
	}

	protected function prepareAdd($data)
	{
		$need = array('type', 'parent_id');
		$discard = array('created', 'updated', 'geo');

		foreach ($discard as $field) {
			if (array_key_exists($field, $data)) unset($data[$field]);
		}

		foreach ($need as $field) {
			if (!array_key_exists($field,$data)) {
				throw new \Exception('need more params: '. $field);
			}
		}

		return $data;
	}

	protected function checkEtag()
	{
		if ($this->request->params['action'] != 'promotions') {
			parent::checkEtag();
		}
	}

	protected function paginate($params, $url, $url_params, $reduce = null)
	{
		$items = Items::find('all', $params)->to('array');
		$response = [];

		if ($reduce) {
			$response['items'] = array_reduce($items, $reduce, array());
		} else {
			$response['items'] = array_map(function($item) {
				$classname = '\app\models\items\\' .
					Inflector::camelize($item['type']);
				return $classname::create($item)->toJSON();
			}, $items);
		}

		$count = Items::find('count', ['conditions' => $params['conditions']]);
		$pages = ceil($count / $params['limit']);
		$meta = [];

		if ($params['page'] > 1) {
			$meta['previous'] = $this->parseUrl($url, $url_params + [
				'page' => $params['page'] - 1]);
		}

		if ($pages > $params['page']) {
			$meta['next'] = $this->parseUrl($url, $url_params + [
				'page' => $params['page'] + 1]);
		}

		if (!empty($meta)) $response['_meta'] = $meta;

		return $response;
	}

	protected function parseUrl($url, $params)
	{
		$query = http_build_query($params);
		return $url . '?' . $query;
	}
}
