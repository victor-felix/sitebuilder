<?php

require_once 'lib/core/security/Security.php';
require_once 'lib/core/storage/Session.php';

class Auth {
    const SESSION_KEY = 'Auth.user';
    
    public static function login($user) {
        Session::regenerate();
        Session::write(self::SESSION_KEY, serialize($user));
    }
    
    public static function logout() {
        Session::destroy();
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
        return !is_null(Session::read(self::SESSION_KEY));
    }
    
    public static function user() {
        $model = Model::load('Users');
        $serialized = unserialize(Session::read(self::SESSION_KEY));
        return new Users($serialized->data());
    }
}
