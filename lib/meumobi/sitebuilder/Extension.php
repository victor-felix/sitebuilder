<?php

namespace meumobi\sitebuilder;

use app\models\Extensions;

class Extension
{
	protected $attr;

	public static function find($id, $scope = null)
	{
		// TODO
		$scope = array('conditions' => array('site_id' => $scope->site));
		$extension = Extensions::find('first', $scope);
		return new static($extension->to('array'));
	}

	public static function findAll($scope)
	{
		// TODO
	}

	public static function findBySite($siteId, $scope = null)
	{
		// TODO
		$conditions = array('site_id' => $siteId);
		$extensions = Extensions::find('all', array(
			'conditions' => $conditions
		));
		return array_map(function($extension) {
			return new Extension($extension);
		}, $extensions->to('array'));
	}

	public static function findByCategory($categoryId, $scope = null)
	{
		// TODO
		$conditions = array('category_id' => $categoryId);
		$extensions = Extensions::find('all', array(
			'conditions' => $conditions
		));
		return array_map(function($extension) {
			return new Extension($extension);
		}, $extensions->to('array'));
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

	public function isValid()
	{
		$keys = array_keys($this->attr);
		$required = array('site_id', 'category_id', 'extension', 'itemLimit',
			'language');
		$diff = array_diff($required, $keys);
		return count($diff) == 0;
	}

	public function save()
	{
		if ($this->isValid()) {
			$extension = Extensions::create();
			$extension->set($this->attr);
			$extension->save();
			$this->attr['id'] = $extension->id();
			return true;
		} else {
			return false;
		}
	}
}
