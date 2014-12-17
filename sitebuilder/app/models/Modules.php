<?php

namespace app\models;

use Model;
use Inflector;

class Modules extends \lithium\data\Model
{
	protected $fields;

	protected $_meta = array(
		'name' => null,
		'title' => null,
		'class' => null,
		'connection' => 'default',
		'initialized' => false,
		'key' => '_id',
		'locked' => false
	);

	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'site_id' => array('type' => 'integer', 'null' => false),
		'created' => array('type' => 'date', 'default' => 0),
		'modified' => array('type' => 'date', 'default' => 0),
	);

	public function id($entity)
	{
		if ($entity->_id) {
			return $entity->_id->{'$id'};
		}
	}

	public function fields($entity)
	{
		return array_keys($this->fields);
	}

	public function field($entity, $field)
	{
		if(array_key_exists($field, $this->fields)) {
			return (object) $this->fields[$field];
		}
	}

	public static function addTimestamps($self, $params, $chain)
	{
		$extension = $params['entity'];
		$date = date('Y-m-d H:i:s');
		if (!$extension->id()) {
			$extension->created = $date;
		}
		$extension->modified = $date;
		return $chain->next($self, $params, $chain);
	}
}
