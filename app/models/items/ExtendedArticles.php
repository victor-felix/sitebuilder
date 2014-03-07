<?php
namespace app\models\items;

use app\models\Items;

class ExtendedArticles extends Articles {
	protected $type = 'ExtendedArticles';
	
	protected $fields = array(
			'title' => array(
					'title' => 'Title',
					'type' => 'string'
			),
			'description' => array(
					'title' => 'Description',
					'type' => 'richtext'
			),
			'author' => array(
					'title' => 'Author',
					'type' => 'string'
			),
			'pubdate' => array(
					'title' => 'Publication date',
					'type' => 'datetime'
			),
			'enclosure' => array(
					'title' => 'Enclosure',
					'type' => 'string'
			),
	);
	
	public static function __init()
	{
		parent::__init();
	
		$self = static::_object();
		$parent = parent::_object();
	
		$self->_schema = $parent->_schema + array(
			'guid' => array('type' => 'string', 'default' => ''),
			'link' => array('type' => 'string', 'default' => ''),
			'pubdate' => array('type' => 'datetime', 'default' => 0),
			'description' => array('type' => 'string', 'default' => ''),
			'author' => array('type' => 'string', 'default' => ''),
			'enclosure' => array('type' => 'string', 'default' => ''),
		);
	}
}

ExtendedArticles::applyFilter('remove', function($self, $params, $chain) {
	return Items::updateOrdering($self, $params, $chain);
});

ExtendedArticles::applyFilter('remove', function($self, $params, $chain) {
	return Items::removeImages($self, $params, $chain);
});

ExtendedArticles::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

ExtendedArticles::applyFilter('save', function($self, $params, $chain) {
	return Items::addOrder($self, $params, $chain);
});