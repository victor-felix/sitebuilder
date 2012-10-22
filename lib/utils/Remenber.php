<?php

use \lithium\storage\Session;
require_once 'Date.php';
require_once 'Auth.php';

class Remenber extends \lithium\data\Model  {
	
	protected $getters = array();
	protected $setters = array();
	protected $_meta = array(
			'name' => null,
			'title' => null,
			'class' => null,
			'source' => 'remenber',
			'connection' => 'default',
			'initialized' => false,
			'key' => '_id',
			'locked' => false
	);
	
	protected $_schema = array(
			'_id'  => array('type' => 'id'),
			'session_id'  => array('type' => 'string', 'null' => false),
			'user_id' => array('type' => 'integer', 'null' => false),
			'expire'  => array('type' => 'date', 'default' => 0),
			'created'  => array('type' => 'date', 'default' => 0),
			'modified'  => array('type' => 'date', 'default' => 0),
			);
	
	public static function addTimestamps($self, $params, $chain) {
		$item = $params['entity'];
	
		if(!$item->id) {
			$item->created = date('Y-m-d H:i:s');
		}
	
		$item->modified = date('Y-m-d H:i:s');
	
		return $chain->next($self, $params, $chain);
	}
	
	
	public static  function check(){
		if(!$remenber = self::loadBySessionId())
			return false;
		
		return $remenber->regenerate($remenber->user);		
	}
	
	public static function loadBySessionId($id = false){
		
		if( !$id  ) {
			if(isset($_COOKIE[ session_name() ]))
				$id = $_COOKIE[ session_name() ];
			else 
				return false;
		}
		
		$item = self::findAllBySessionId($id)->next();
		
		if(!$item)
			return false;
		
		$remenber = self::create($item->to('array'));
		return $remenber;
	}
	
	public function loadByUser($user) {
		
		$item = self::findAllByUserId($user->id())->next();
		
		if(!$item)
			return false;
		
		$remenber = self::create($item->to('array'));
		
		return $remenber;
	}
	
	public static function regenerate($item){
		try{
			session_regenerate_id();
			$user = Model::load('Users')->firstById($item->user_id);
			Session::write(Auth::SESSION_KEY, serialize($user));
			//update last login
			$user->update(array(
					'conditions' => array('id' => $user->id)
			), array(
					'last_login' => date('Y-m-d H:i:s'),
			));
			
			$itemArr =  $item->to('array');
			return self::add($user, $itemArr['_id']);
		}catch (\Exception $e){
			return false;
		}
	}
	
	public static function add($user, $id = false){
		$data = array();
		$lifetime = time() + Date::$convert['months'] * 3;
		setcookie(session_name(), session_id(),  $lifetime, '/');
		
		if($id)
			return self::update(array(
					'expire' => date('Y-m-d H:i:s',$lifetime),
					'session_id' => session_id()	
					), 
					array( '_id' => $id ) );
		/*
		if($item = self::findAllByUserId($user->id())->next() ){
			$item = $item->to('array');
			return self::update(array(	'session_id' => session_id()	), array( '_id' => $item['_id']	) );
		}*/
		
		$remenber 					= self::create();
		$remenber->user_id 	= $user->id() ;
		$remenber->expire 	= date('Y-m-d H:i:s',$lifetime);
		$remenber->session_id  = session_id();
		
		return  $remenber->save();
	}
	
	public static function clean($id = false){
		if( !$id  ) {
			if(isset($_COOKIE[ session_name() ]))
				$id = $_COOKIE[ session_name() ];
			else
				return true;
		}
		setcookie(session_name(), session_id(),  0, '/');
		self::remove( array('session_id' => $id ));
	}
	
}

Session::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});
