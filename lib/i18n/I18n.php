<?php

require_once 'lib/yaml_dictionary/YamlDictionary.php';

class I18n extends YamlDictionary
{
	const DEFAULT_LANG = 'en';
	protected static $path = 'config/locales';
	protected static $dictionary;
	protected static $yaml;
	protected static $availableLanguages;

	public static function locale($locale = null)
	{
		if (!in_array($locale, self::availableLanguages())) {
			$locale = self::DEFAULT_LANG;
		}
		return static::dictionary($locale);
	}

	public static function availableLanguages()
	{
		if(self::$availableLanguages) return self::$availableLanguages;

		return self::$availableLanguages = array_map(function($path) {
			return basename($path, '.yaml');
		}, glob(self::path() . '/*.yaml'));
	}
}
