<?php
namespace app\models;

use Model;
use Inflector;

class Plugins extends Modules
{
	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();
		$self->_meta = $parent->_meta + array('source' => 'plugins');
		$self->_schema = $parent->_schema + array(
			'plugin' => array('type' => 'string', 'null' => false),
			'options'=> array('type' => 'array'),
		);
	}
}

Plugins::applyFilter('save', function($self, $params, $chain) {
	return Plugins::addTimestamps($self, $params, $chain);
});
