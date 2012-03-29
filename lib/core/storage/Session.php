<?php

class Session extends \lithium\storage\Session {
    public static function writeFlash($key, $value) {
        $flash = self::read('Flash.' . $key);
        $flash[] = $value;
        self::write('Flash.' . $key, $flash);
    }

    public static function flash($key, $value = null) {
        if(!is_null($value)) {
            $flash = self::read('Flash.' . $key);
            $flash[] = $value;
            return self::writeFlash($key, $flash);
        }
        else {
            $value = self::read('Flash.' . $key);
            self::delete('Flash.' . $key);
            return $value;
        }
    }
}
