<?php
namespace app\models\items;

use Model;
use app\models\Items;

class Articles extends \app\models\Items
{
	protected $type = 'Article';

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
		'groups' => array(
			'title' => 'Group',
			'type' => 'groups'
		),
		'published' => array(
			'title' => 'Publish at',
			'type' => 'datetime'
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
			'description' => array('type' => 'string', 'default' => ''),
			'author' => array('type' => 'string', 'default' => ''),
			'medias' => array('type' => 'array', 'default' => array()),
		);
	}
}

Articles::applyFilter('remove', function($self, $params, $chain) {
	return Items::updateOrdering($self, $params, $chain);
});

Articles::applyFilter('remove', function($self, $params, $chain) {
	return Items::removeImages($self, $params, $chain);
});

Articles::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

Articles::applyFilter('save', function($self, $params, $chain) {
	return Items::addThumbnails($self, $params, $chain);
});

Articles::applyFilter('save', function($self, $params, $chain) {
	return Items::addOrder($self, $params, $chain);
});

Articles::applyFilter('save', function($self, $params, $chain) {
	return Items::sendPushNotification($self, $params, $chain);
});
