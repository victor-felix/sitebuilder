<?php

class CategoriesController extends AppController
{
	protected function beforeFilter()
	{
		parent::beforeFilter();

		if (MeuMobi::currentSegment()->hideCategories) {
			$this->redirect('/sites/business_info');
		}
	}

	public function index()
	{
		$_categories = $this->getCurrentSite()->categories();
		$categories = array();
		foreach ($_categories as $category) {
			$categories[$category->parent_id] []= $category;
		}

		$this->set(compact('categories'));
	}

	public function add($parent_id = null)
	{
		$site = $this->getCurrentSite();
		$category = new Categories($this->data);
		$category->parent_id = $parent_id;
		$category->site_id = $site->id;

		if (!empty($this->data)) {
			if ($category->validate()) {
				$category->save();
				if ($this->isXhr()) {
					$this->respondToJSON(array(
						'go_back' => true,
						'refresh' => '/categories',
						'success' => s('Category successfully added.')
					));
				} else {
					Session::writeFlash('success', s('Category successfully added.'));
					$this->redirect('/categories');
				}
			} else {
				if ($this->isXhr()) {
					$this->respondToJSON(array(
						'refresh' => '/categories/add/' . $parent_id,
						'error' => s('Sorry, we can\'t save the category')
					));
				} else {
					Session::writeFlash('error', s('Sorry, we can\'t save the category'));
				}
			}
		}

		$this->set(compact('category', 'site'));
	}

	public function edit($id = null)
	{
		$site = $this->getCurrentSite();
		$category = Model::load('Categories')->firstById($id);

		if (!empty($this->data)) {
			$category->updateAttributes($this->data);
			if ($category->validate()) {
				$category->save();
				if ($this->isXhr()) {
					$this->respondToJSON(array(
						'go_back' => true,
						'refresh' => '/categories',
						'success' => s('Category successfully updated.')
					));
				} else {
					Session::writeFlash('success', s('Category successfully updated.'));
					$this->redirect('/categories');
				}
			}
		}

		$this->set(compact('category', 'site'));
	}

	public function delete($id = null)
	{
		Model::load('Categories')->delete($id);
		$message = s('Category successfully deleted.');
		if ($this->isXhr()) {
			$this->respondToJSON(array(
				'success' => $message,
				'go_back' => true,
				'refresh' => '/categories'
			));
		} else {
			Session::writeFlash('success', $message);
			$this->redirect('/categories');
		}
	}

	public function delete_all_items($id = null)
	{
		$category = Model::load('Categories')->firstById($id);
		$status = 'error';
		$message = s('Sorry, we can\'t remove items');

		if ($category) {
			$category->removeItems();
			$status = 'success';
			$message = s('Category Items deleted successfully.');
		}

		if ($this->isXhr()) {
			$json = array($status => $message);
			$this->respondToJSON($json);
		} else {
			Session::writeFlash($status, $message);
			$this->redirect('/categories');
		}
	}

	public function moveup($id = null)
	{
		Model::load('Categories')->firstById($id)->moveUp();
		$this->redirect('/categories');
	}

	public function movedown($id = null)
	{
		Model::load('Categories')->firstById($id)->moveDown();
		$this->redirect('/categories');
	}
}
