<?php

namespace meumobi\sitebuilder;

use Model; // TODO
use Categories; // TODO
use app\models\Items; // TODO

class Category
{
	protected $attr;

	public static function find($id, $scope = null)
	{
		// TODO
		$scope = array('conditions' => array('site_id' => $scope->site));
		$category = Model::load('Categories')->firstById($id, $scope);
		return new static($category->data);
	}

	public static function findAll($scope)
	{
		$conditions = (array) $scope;
		$categories = Model::load('Categories')->all(array(
			'conditions' => $conditions
		));
		return array_map(function($category) {
			return new Category($category->data);
		}, $categories);
	}

	public static function findBySite($siteId, $scope = null)
	{
		// TODO
		$conditions = array('site_id' => $siteId);

		if (isset($scope->visibility)) {
			if ($scope->visibility === null) {
				$conditions['visibility'] = true;
			} elseif ($scope->visibility != 'all') {
				$conditions['visibility'] = (bool) $scope->visibility;
			}
		}
		if ($scope->root) {
			$conditions['parent_id'] = 0;
		}
		$categories = Model::load('Categories')->all(array(
			'conditions' => $conditions
		));
		return array_map(function($category) {
			return new Category($category->data);
		}, $categories);
	}

	public function __construct($attr = array())
	{
		$this->attr = $attr;
	}

	public function __get($attr)
	{
		return $this->attr[$attr];
	}

	public function attributes()
	{
		return $this->attr;
	}

	public function countItems($scope = null)
	{
		// TODO
		return Items::find('count', array('conditions' => array(
			'parent_id' => $this->id
		)));
	}

	public function children($options = array(), $scope = null)
	{
		$scope = (object) array('parent_id' => $this->id, 'visibility' => 1);
		$results = static::findAll($scope);

		if ($options['depth'] > 0) {
			foreach ($results as $result) {
				$children = $result->children(array('depth' => $options['depth'] - 1));
				$results = array_merge($results, $children);
			}
		}
		//if($depth > 0) {
			//foreach($results as $result) {
				//$children = $this->recursiveByParentId($result->id, $depth - 1);
				//$results = array_merge($results, $children);
			//}
		//}

		return $results;
	}
}
