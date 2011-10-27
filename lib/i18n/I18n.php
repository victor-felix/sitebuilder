<?php

require_once 'lib/yaml_dictionary/YamlDictionary.php';

class I18n extends YamlDictionary {
    protected static $path = 'config/locales';
    protected static $dictionary;
    protected static $yaml;

    public static function locale($locale = null) {
        return static::dictionary($locale);
    }
}
