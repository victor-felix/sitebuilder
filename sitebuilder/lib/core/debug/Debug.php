<?php

use meumobi\sitebuilder\Logger;

class Debug {
    public static function handleErrors($handler = null) {
        if(is_null($handler)) {
            $handler = array('Debug', 'handleError');
        }

        set_error_handler($handler, -1);
    }

    public static function handleError($code, $message, $file, $line) {
        throw new ErrorException($message, 0, $code, $file, $line);
    }

    public static function log($message) {
        return Logger::debug('general', $message);
    }

    public static function pr($data) {
        echo '<pre>' . print_r($data, true) . '</pre>' . PHP_EOL;
    }

    public static function dump($data) {
        self::pr(var_export($data, true));
    }

    public static function trace() {
        return debug_backtrace();
    }
}

function pr($data) {
    Debug::pr($data);
}

function dump($data) {
    Debug::dump($data);
}
