<?php

class MeuMobi {
    protected static $segment;

    public static function segment($segment) {
        if(is_null($segment)) {
            return static::$segment;
        }
        else {
            static::$segment = $segment;
            YamlDictionary::dictionary($segment);
        }
    }
}
