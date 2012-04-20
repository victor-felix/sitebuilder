<?php

class MeuMobi
{
	protected static $segment;

	public static function segment($segment = null)
	{
		if (is_null($segment)) {
			return static::$segment;
		} else {
			static::$segment = $segment;
			YamlDictionary::dictionary($segment);
		}
	}

	public static function instance()
	{
		$domain = Mapper::domain();
		return substr($domain, strpos($domain, '/') + 2);
	}

	public static function domain()
	{
		$domain = self::instance();
		return substr($domain, strpos($domain, '.') + 1);
	}
}
