<?php
namespace app\models\items;
use app\models\Items;

class Users extends \app\models\Items {
	protected $type = 'User';

	protected $fields = array(
			'firstname' => array(
					'title' => 'First name',
					'type' => 'string'
			),
			'lastname' => array(
					'title' => ' Last name',
					'type' => 'string'
			),
			'mail' => array(
					'title' => 'Mail',
					'type' => 'string'
			),
			'password' => array(
					'title' => 'Password',
					'type' => 'string'
			),
			'phone' => array(
					'title' => 'Phone',
					'type' => 'string'
			),
			'role' => array(
					'title' => 'role',
					'type' => 'select',
					'empty'=> '',
					'options' => array('Patron' => 'Patron','Employé' => 'Employé','Particulier'=>'Particulier'),
			),
			'points' => array(
					'title' => 'points',
					'type' => 'string'
			),
			'business_id' => array(
					'title' => 'Business',
					'type' => array('related', 'Business'),
					'multiple' => false,
					'empty'=> '',
					'name' => 'business_id',
					'class' => 'ui-select large'
			),
			'pushId' => array(
					'title' => 'pushId',
					'type' => 'string'
			),
			'group' => array(
				'title' => 'Group',
				'type' => 'string'
			),
	);
	
	public static function __init() {
		parent::__init();
	
		$self = static::_object();
		$parent = parent::_object();
	
		$self->_schema = $parent->_schema + array(
				'title' => array('type' => 'string', 'default' => ''),
				'firstname'  => array('type' => 'string', 'default' => ''),
				'lastname'  => array('type' => 'string', 'default' => ''),
				'mail'  => array('type' => 'string', 'default' => ''),
				'password'  => array('type' => 'string', 'default' => ''),
				'phone'  => array('type' => 'string', 'default' => ''),
				'role'  => array('type' => 'string', 'default' => ''),
				'points' => array('type' => 'integer', 'default' => 0),
				'business_id' => array('type' => 'string', 'default' => ''),
				'pushId' => array('type' => 'string', 'default' => ''),
		);
	}
}

Users::applyFilter('save', function($self, $params, $chain) {
	$item = $params['entity'];
	$item->title =$item->lastname . ', ' . $item->firstname;
	return $chain->next($self, $params, $chain);
});

Users::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});
