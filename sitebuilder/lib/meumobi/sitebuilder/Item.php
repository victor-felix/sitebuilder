<?php

namespace meumobi\sitebuilder;

use app\models\Items; // TODO

class Item
{
	protected $attr;

	public static function count($scope = null)
	{
		// TODO
		return Items::find('count', array('conditions' => array(
			'parent_id' => $scope->category
		)));
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

}
