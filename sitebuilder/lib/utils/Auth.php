<?php

require_once 'lib/core/security/Security.php';
require_once 'lib/utils/Date.php';
require_once 'lib/utils/Remenber.php';

use \lithium\storage\Session;

class Auth {
	const SESSION_KEY = 'Auth.user';
	
	public static function login($user, $remember = false) {
		//start the session, can't find another way
		Session::read('');
		session_regenerate_id();
		
		if($remember) {
			Remenber::add($user);
		}else{
			Remenber::clean();
		}
		
		if ($user->id) {
			//update last login
			$user->update(array(
					'conditions' => array('id' => $user->id)
			), array(
					'last_login' => date('Y-m-d H:i:s'),
			));
		}
		
		Session::write(self::SESSION_KEY, serialize($user));
	}
	
	public static function logout() {
		Session::clear();
		Remenber::clean();
	}

	public static function identify($data) {
		return Model::load('Users')->first(array(
			'conditions' => array(
				'email' => $data['email'],
				'password' => Security::hash($data['password'], 'sha1')
			)
		));
	}

	public static function loggedIn() {
		return (bool) Session::read(self::SESSION_KEY) || Remenber::check();
	}

	public static function user() {
		$model = Model::load('Users');
		$serialized = unserialize(Session::read(self::SESSION_KEY));
		return new Users($serialized->data());
	}
}
