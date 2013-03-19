<?php

namespace app\models\items;

use app\models\Items;

class MerchantProducts extends Items
{
	protected $type = 'MerchantProducts';

	protected $fields = array(
		'title' => array(
			'title' => 'Title',
			'type' => 'string'
		),

		'brand' => array(
			'title' => 'Brand',
			'type' => 'string'
		),

		'description' => array(
			'title' => 'Description',
			'type' => 'string'
		),

		'price' => array(
			'title' => 'Price',
			'type' => 'string'
		),

		'availability' => array(
			'title' => 'Availability',
			'type' => 'string'
		),

		'link' => array(
			'title' => 'Link',
			'type' => 'string'
		),
	);

	public static function __init()
	{
		parent::__init();

		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'title' => array('type' => 'string', 'default' => '')
			'brand' => array('type' => 'string', 'default' => '')
			'description' => array('type' => 'string', 'default' => '')
			'price' => array('type' => 'string', 'default' => '')
			'availability' => array('type' => 'string', 'default' => '')
			'link' => array('type' => 'string', 'default' => '')
			'product_type' => array('type' => 'string', 'default' => '')
		);
	}
}

MerchantProducts::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});
