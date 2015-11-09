<?php

namespace app\models\items;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

use app\models\Items;

class Files extends Items
{
	protected $type = 'Files';

	protected $fields = array(
		'title' => array(
			'title' => 'Title',
			'type' => 'string'
		),

		'description' => array(
			'title' => 'Description',
			'type' => 'richtext'
		),

	);

	public static function __init()
	{
		parent::__init();

		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'title' => array('type' => 'string', 'default' => ''),
			'description' => array('type' => 'string', 'default' => ''),
		);
	}
}

Files::applyFilter('remove', function($self, $params, $chain) {
	return Items::updateOrdering($self, $params, $chain);
});

Files::applyFilter('remove', function($self, $params, $chain) {
	return Items::removeImages($self, $params, $chain);
});

Files::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});

Files::applyFilter('save', function($self, $params, $chain) {
	return Items::addThumbnails($self, $params, $chain);
});
