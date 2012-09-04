<?php

namespace app\controllers\api;

use app\presenters\CategoryPresenter;
use meumobi\sitebuilder\Category;
use meumobi\sitebuilder\Site;

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
		$domain = $this->request->params['slug'];
		return Site::findByDomain($domain);
	}

	public function index()
	{
		$scope = (object) array('visibility' => $this->query('visibility'));
		$categories = $this->site()->categories($scope);

		return $this->toJSON($categories);
	}

	public function show()
	{
		$category = $this->site()->findCategory($this->request->params['id']);
		$category = new CategoryPresenter($category);

		return $category->toJSON();
	}

	public function children()
	{
		if (isset($this->request->params['id'])) {
			$category = $this->site->findCategory($this->request->params['id']);
		} else {
			$category = $this->site->findRootCategory();
		}

		$categories = $category->children(array('depth' => $this->param('depth', 0)));
		return $this->toJSON($categories);
	}

	public function create() {
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
		Model::load('Categories')->delete($this->param('id'));
		$this->response->status(200);
	}
}
