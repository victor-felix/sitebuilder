<?php

class Session {
    protected static $cookieParams = array(
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true
    );
    
    public static function start() {
        if(!self::started()) {
            extract(self::$cookieParams);
            session_set_cookie_params(
                $lifetime,
                $path,
                $domain,
                $secure,
                $httponly
            );
            return session_start();
        }
    }
    
    public static function started() {
        return isset($_SESSION);
    }
    
    public static function read($name) {
        self::start();
        
        if(array_key_exists($name, $_SESSION)) {
            return $_SESSION[$name];
        }

        return null;
    }
    
    public static function write($name, $value) {
        self::start();
        
        $_SESSION[$name] = $value;
    }
    
    public static function delete($name) {
        self::start();

        unset($_SESSION[$name]);
    }
    
    public static function writeFlash($key, $value) {
        self::write('Flash.' . $key, $value);
    }
    
    public static function flash($key, $value = null) {
        if(!is_null($value)) {
            return self::writeFlash($key, $value);
        }
        else {
            $value = self::read('Flash.' . $key);
            self::delete('Flash.' . $key);
            return $value;
        }
    }
    
    public static function id() {
        self::start();
        
        return session_id();
    }
    
    public static function regenerate() {
        self::start();

        session_regenerate_id();
    }
    
    public static function destroy() {
        self::start();

        session_destroy();
    }
}