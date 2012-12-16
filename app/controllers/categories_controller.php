<?php

class CategoriesController extends AppController
{
	protected function beforeFilter()
	{
		if (Auth::user()->site()->hide_categories) {
			$this->redirect('/sites/business_info');
		}
	}

	public function index() {
		$categories = $this->getCurrentSite()->categories();
		$tree = array();
		foreach($categories as $category) {
			$tree[$category->parent_id] []= $category;
		}

		$this->set(array(
			'categories' => $tree,
			'root' => $this->getCurrentSite()->rootCategory()
		));
	}

	public function add($parent_id = null) {
		$site = $this->getCurrentSite();
		$category = new Categories($this->data);
		if(!empty($this->data)) {
			$category->site_id = $site->id;
			if($category->validate()) {
				$category->save();
				if($this->isXhr()) {
					$json = array(
						'go_back'=>true,'refresh'=>'/categories',
						'success'=>s('Category successfully added.')
					);
					$this->respondToJSON($json);
				}
				else {
					Session::writeFlash('success', s('Category successfully added.'));
					$this->redirect('/categories');
				}
			}else{
				if($this->isXhr()) {
					$json = array(
						'refresh'=>'/categories/add/' . $parent_id,
						'error'=>s('Sorry, we can\'t save the category')
					);
					$this->respondToJSON($json);
				}
				else {
					Session::writeFlash('error', s('Sorry, we can\'t save the category'));
					$this->redirect('/categories/add/' . $parent_id);
				}
			}
		}

		$this->set(array(
			'category' => $category,
			'parent' => $this->Categories->firstById($parent_id),
			'site' => $this->getCurrentSite()
		));
	}

	public function edit($id = null) {
		$site = $this->getCurrentSite();
		$category = $this->Categories->firstById($id);
		if(!empty($this->data)) {
			$category->updateAttributes($this->data);
			if($category->validate()) {
				$category->save();
				if($this->isXhr()) {
					$json = array(
						'go_back'=>true,'refresh'=>'/categories',
						'success'=>s('Category successfully updated.')
					);
					$this->respondToJSON($json);
				}
				else {
					Session::writeFlash('success', s('Category successfully updated.'));
					$this->redirect('/categories');
				}
			}
		}

		$this->set(array(
			'category' => $category,
			'parent_id' => $category->parent(),
			'site' => $site
		));
	}

	public function delete($id = null) {
		$this->Categories->delete($id);
		$message = s('Category successfully deleted.');
		if($this->isXhr()) {
			$json = array(
				'success'=>$message,
				'go_back'=>true,
				'refresh'=>'/categories'
			);
			$this->respondToJSON($json);
		}
		else {
			Session::writeFlash('success', $message);
			$this->redirect('/categories');
		}
	}

	public function delete_all_items($id = null) {
		$category = $this->Categories->firstById($id);
		$status = 'error';
		$message = "Sorry, we can't remove items";

		if($category) {
			$classname = '\app\models\items\\' . Inflector::camelize($category->type);
			$classname::remove(array('parent_id' => $category->id));
			$status = 'success';
			$message = s('Category Items deleted successfully.');
		}
		if($this->isXhr()) {
			$json = array($status => $message);
			$this->respondToJSON($json);
		}
		else {
			Session::writeFlash($status, $message);
			$this->redirect('/categories');
		}
	}

	public function reorder() {
		$this->Categories->resetOrder($this->getCurrentSite()->id);
		$status = 'success';
		$message = s('Categories were reordered successfully');
		if($this->isXhr()) {
			$json = array($status => $message);
			$this->respondToJSON($json);
		}
		else {
			Session::writeFlash($status, $message);
			$this->redirect('/categories');
		}
	}

	public function moveup($id = null) {
		$category = $this->Categories->firstById($id);
		$category->moveUp();

		if($this->isXhr()) {
			$json = array();
			$this->respondToJSON($json);
		}
		else {
			Session::writeFlash($status, $message);
			$this->redirect('/categories');
		}
	}

	public function movedown($id = null) {
		$category = $this->Categories->firstById($id);
		$category->moveDown();

		if($this->isXhr()) {
			$json = array();
			$this->respondToJSON($json);
		}
		else {
			Session::writeFlash($status, $message);
			$this->redirect('/categories');
		}
	}
}
