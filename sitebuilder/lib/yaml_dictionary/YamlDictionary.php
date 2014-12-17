<?php

require 'lib/yaml/Yaml.php';

class YamlDictionary
{
	protected static $dictionary;
	protected static $path;
	protected static $yaml;

	public static function path($path = null)
	{
		if (is_null($path)) {
			return static::$path;
		} else {
			return static::$path = $path;
		}
	}

	public static function dictionary($dictionary = null)
	{
		if (is_null($dictionary)) {
			return static::$dictionary;
		} else {
			return static::$dictionary = $dictionary;
		}
	}

	public static function translate($key)
	{
		$yaml = static::loadYaml();
		return $yaml->get($key);
	}

	protected static function loadYaml()
	{
		if (is_null(static::$yaml)) {
			$yaml_path = static::$path . '/' . static::$dictionary . '.yaml';
			static::$yaml = new Yaml($yaml_path);
		}

		return static::$yaml;
	}
}
