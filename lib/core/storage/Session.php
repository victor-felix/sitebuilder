<?php

class Session extends \lithium\storage\Session {
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
}
