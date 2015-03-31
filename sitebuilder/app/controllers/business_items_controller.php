<?php

use app\models\Items;

class BusinessItemsController extends AppController
{
	protected $uses = array();

	public function index($parent_id = null)
	{
		$category = Model::load('Categories')->firstById($parent_id);

		$classname = '\app\models\items\\' . Inflector::camelize($category->type);

		$params = array(
			'conditions' => array( 'parent_id' => $category->id),
			'limit' => $this->param('limit', 10),
			'page' => $this->param('page',1),
			'order' => $this->param('order', ['order' => 'DESC' ,'title']),
		);

		$this->set(compact('category') + $classname::paginate($params));
	}

	public function add($parent_id = null)
	{
		$site = $this->getCurrentSite();
		$parent = Model::load('Categories')->firstById($parent_id);
		$classname = '\app\models\items\\' . Inflector::camelize($parent->type);
		$item = $classname::create();
		$item->type = $parent->type;

		if (!empty($this->data)) {
			$this->data = $this->prepareDates($this->data);
			if (!$this->request->get('data:groups')) $this->data['groups'] = [];
			$images = array_unset($this->data, 'image');
			$images = $this->request->data['image'];
			$item->set($this->data);
			$item->parent_id = $parent->id;
			$item->site_id = $site->id;
			$item->type = $parent->type;

			if ($item->save()) {
				foreach ($images as  $id => $image) {
					if (is_numeric($id)) {
						$record = Model::load('Images')->firstById($id);
						if (!$record) continue;
						$record->title = $image['title'];
						$record->foreign_key = $item->id();
						$record->save();
					}
				}

				if (isset($item->geo) && !$item->geo) {
					$message = s('Your items are being processed and will appear on the map shortly.');
				} else {
					$message = s('Item successfully added.');
				}
				if ($this->isXhr()) {
					$this->respondToJSON(array(
						'success' => $message,
						'go_back' => true,
						'refresh' => '/business_items/index/' . $parent_id
					));
				} else {
					Session::writeFlash('success', $message);
					$this->redirect('/business_items/index/' . $item->parent_id);
				}
			}
		}

		$this->set(array(
			'item' => $item,
			'parent' => $parent
		));
	}

	public function edit($id = null)
	{
		$site = $this->getCurrentSite();
		$item = Items::find('type', array('conditions' => array(
			'_id' => $id
		)));

		if (!empty($this->data)) {
			$this->data = $this->prepareDates($this->data);
			//if no group is selected on the multiselect input the property isn't present in the request
			if (!$this->request->get('data:groups')) $this->data['groups'] = [];
			$images = array_unset($this->data, 'image');
			$item->set($this->data);
			$item->site_id = $site->id;

			if ($item->save()) {
				foreach ($images as $id => $image) {
					if (is_numeric($id)) {
						$record = Model::load('Images')->firstById($id);
						$record->title = $image['title'];
						$record->save();
					}
				}
				$message = s('Item successfully updated.');
				if ($this->isXhr()) {
					$this->respondToJSON(array(
						'success' => $message,
						'go_back' => true,
						'refresh' => '/business_items/index/' . $item->parent_id
					));
				} else {
					Session::writeFlash('success', s('Item successfully updated.'));
					$this->redirect('/business_items/index/' . $item->parent_id);
				}
			}
		}

		$this->set(array(
			'parent' => $item->parent(),
			'item' => $item
		));
	}

	public function delete($id = null)
	{
		$item = Items::find('first', array('conditions' => array(
			'_id' => $id
		)));
		$parent_id = $item->parent_id;
		Items::remove(array('_id' => $id));
		$message = s('Item successfully deleted.');

		if ($this->isXhr()) {
			$this->respondToJSON(array(
				'success' => $message,
				'go_back' => true,
				'refresh' => '/business_items/index/' . $parent_id
			));
		} else {
			Session::writeFlash('success', $message);
			$this->redirect('/business_items/index/' . $item->parent_id);
		}
	}

	public function moveup($id)
	{
		$item = Items::find('first', array('conditions' => array(
			'_id' => $id
		)));
		$currentOrder = $item->order;

		if ($item->moveUp()) {
			$status = 'success';
			$message = s('Item successfully moved up');
		} else {
			$status = 'error';
			$message = s('Item not moved up');
		}

		if ($this->isXhr()) {
			$this->respondToJSON(array(
				$status => $message,
				'go_back' => true,
				'refresh' => '/business_items/index/' . $item->parent_id
			));
		} else {
			Session::writeFlash($status, $message);
			$this->redirect('/business_items/index/' . $item->parent_id);
		}
	}

	public function movedown($id)
	{
		$item = Items::find('first', array('conditions' => array(
			'_id' => $id
		)));
		$currentOrder = $item->order;

		if ($item->moveDown()) {
			$status = 'success';
			$message = s('Item successfully moved down');
		} else {
			$status = 'error';
			$message = s('Item not moved down');
		}

		if ($this->isXhr()) {
			$this->respondToJSON(array(
				$status => $message,
				'go_back' => true,
				'refresh' => '/business_items/index/' . $item->parent_id
			));
		} else {
			Session::writeFlash($status, $message);
			$this->redirect('/business_items/index/' . $item->parent_id);
		}
	}

	// this function should be refactored somewhere else. I could not find a way
	// to intercept lithium's casting to add timezones to dates
	protected function prepareDates($data) {
		$fields = ['start', 'end', 'published'];
		$timezone = date_default_timezone_get();
		date_default_timezone_set($this->getCurrentSite()->timezoneId());

		foreach ($fields as $field) {
			if (isset($data[$field])) {
				$data[$field] = strtotime($data[$field]);
			}
		}

		date_default_timezone_set($timezone);

		return $data;
	}
}
