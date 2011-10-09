<?php

require_once 'lib/core/security/Security.php';

use \lithium\storage\Session;

class Auth {
    const SESSION_KEY = 'Auth.user';

    public static function login($user, $remember = false) {
        if($remember) {
            $lifetime = 30 * 86400;
            session_set_cookie_params($lifetime);
        }

        session_regenerate_id();
        Session::write(self::SESSION_KEY, serialize($user));
    }

    public static function logout() {
        Session::clear();
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
        return (bool) Session::read(self::SESSION_KEY);
    }

    public static function user() {
        $model = Model::load('Users');
        $serialized = unserialize(Session::read(self::SESSION_KEY));
        return new Users($serialized->data());
    }
}
