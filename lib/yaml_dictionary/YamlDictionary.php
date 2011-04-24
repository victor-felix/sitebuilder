<?php

require 'lib/yaml/Yaml.php';

class YamlDictionary {
    protected static $dictionary;
    protected static $path;

    public static function path($path = null) {
        if(is_null($path)) {
            return $path;
        }
        else {
            static::$path = $path;
        }
    }

    public static function dictionary($dictionary = null) {
        if(is_null($dictionary)) {
            return $dictionary;
        }
        else {
            static::$dictionary = $dictionary;
        }
    }

    public static function translate($key) {
        $yaml = static::loadYaml();
        return $yaml->get($key);
    }

    protected static function loadYaml() {
        $yaml_path = static::$path . '/' . static::$dictionary . '.yaml';
        return new Yaml($yaml_path);
    }
}
