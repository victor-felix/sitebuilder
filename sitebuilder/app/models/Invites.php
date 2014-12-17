<?php

namespace app\models;

class Invites extends \lithium\data\Model
{
	protected $_meta = array(
		'name' => null,
		'title' => null,
		'class' => null,
		'source' => 'invites',
		'connection' => 'default',
		'initialized' => false,
		'key' => '_id',
		'locked' => false,
	);

	protected $_schema = array(
		'_id'  => array('type' => 'id'),
		'site_id' => array('type' => 'integer', 'null' => false),
		'host_id' => array('type' => 'integer', 'null' => false),
		'email'  => array('type' => 'string', 'null' => false),
		'token'  => array('type' => 'string', 'null' => false),
		'created'  => array('type' => 'date', 'default' => 0),
		'modified'  => array('type' => 'date', 'default' => 0),
	);

	public static function addTimestamps($self, $params, $chain)
	{
		$item = $params['entity'];
		if (!$item->_id) {
			$item->created = date('Y-m-d H:i:s');
		}
		$item->modified = date('Y-m-d H:i:s');
		return $chain->next($self, $params, $chain);
	}
}

Invites::applyFilter('save', function($self, $params, $chain) {
	return Invites::addTimestamps($self, $params, $chain);
});
