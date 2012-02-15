<?php

namespace meumobi\sitebuilder;

use Sites; // TODO
use Model; // TODO

class Site
{
	protected $attr;

	public static function findByDomain($domain)
	{
		// TODO
		$domain = Model::load('Sites')->firstByDomain($domain);
		return new static($domain->data);
	}

	public function __construct($attr = array())
	{
		$this->attr = $attr;
	}

	public function categories($scope = null)
	{
		return Category::findBySite($this->attr['id'], $scope);
	}

	public function findCategory($categoryId)
	{
		$scope = (object) array('site' => $this->attr['id']);
		return Category::find($categoryId, $scope);
	}

	public function findRootCategory()
	{
		$scope = (object) array('root' => true);
		$categories = Category::findBySite($this->attr['id'], $scope);
		return $categories[0];
	}
}
