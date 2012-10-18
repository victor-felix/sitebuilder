<?php

namespace app\models;

use meumobi\sitebuilder\Extension;

class Extensions extends \lithium\data\Model
{
	protected $_meta = array(
		'name' => null,
		'title' => null,
		'class' => null,
		'source' => 'extensions',
		'connection' => 'default',
		'initialized' => false,
		'key' => '_id',
		'locked' => false
	);

	protected $_schema = array(
		'_id'  => array('type' => 'id'),
		'extension' => array('type' => 'string', 'null' => false),
		'site_id' => array('type' => 'integer', 'null' => false),
		'category_id' => array('type' => 'integer', 'null' => false),
        'created'  => array('type' => 'date', 'default' => 0),
        'modified'  => array('type' => 'date', 'default' => 0)
	);

	public function id($entity)
	{
		if ($entity->_id) {
			return $entity->_id->{'$id'};
		}
	}

	public static function addTimestamps($self, $params, $chain)
	{
		$item = $params['entity'];

		if (!$item->id()) {
			$item->created = date('Y-m-d H:i:s');
		}

		$item->modified = date('Y-m-d H:i:s');

		return $chain->next($self, $params, $chain);
	}
}

Extensions::applyFilter('save', function($self, $params, $chain) {
	return Extensions::addTimestamps($self, $params, $chain);
});
