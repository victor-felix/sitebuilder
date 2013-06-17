<?php

namespace app\controllers\api;

use app\presenters\CategoryPresenter;
use meumobi\sitebuilder\Category;
use meumobi\sitebuilder\Site;
use app\models\Items;
use Model;
use Inflector;

class CategoriesController extends ApiController
{
	protected function query($key)
	{
		if (isset($this->request->query[$key])) {
			return $this->request->query[$key];
		}
	}

	protected function site()
	{
		return Site::findByDomain($this->request->get('params:slug'));
	}

	public function index()
	{
		$scope = (object) array('visibility' => $this->request->get('query:visibility'));
		$categories = $this->site()->categories($scope);

		return $this->toJSON($categories);
	}

	public function show()
	{
		$category = $this->site()->findCategory($this->request->get('params:id'));
		$category = new CategoryPresenter($category);

		return $category->toJSON();
	}

	public function showNewsCategory() {
		$site = \Model::load('Sites')->firstByDomain($this->request->get('params:slug'));
		return $this->toJSON($site->newsCategory());
	}

	public function children()
	{
		if (isset($this->request->params['id'])) {
			$category = $this->site()->findCategory($this->request->params['id']);
			$categories = $category->children(array('depth' => $this->param('depth', 0)));
			return $this->toJSON($categories);
		} else {
			return $this->toJSON($this->site()->categories());
		}

	}

	public function create() {
		$this->requireUserAuth();
		$category = $this->site()->buildCategory($this->request->data);

		if($category->save()) {
			$this->response->status(201);
			$category = new CategoryPresenter($category);
			return $category->toJSON();
		}
		else {
			$this->response->status(422);
		}
	}

	public function update() {
		$this->requireUserAuth();
		$category = Model::load('Categories')->firstBySiteIdAndId($this->site()->id, $this->param('id'));
		$category->updateAttributes(array(
			'site_id' => $this->site()->id
		) + $this->request->data);

		if($category->validate()) {
			$category->save();
			$this->response->status(200);
			return $this->toJSON($category);
		}
		else {
			$this->response->status(422);
		}
	}

	public function destroy() {
		$this->requireUserAuth();
		Model::load('Categories')->delete($this->param('id'));
		$this->response->status(200);
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
}
