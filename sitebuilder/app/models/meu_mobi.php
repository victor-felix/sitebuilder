<?php

require_once 'app/models/segments.php';

class MeuMobi
{
	protected static $segment;

	public static function segment()
	{
		return self::currentSegment()->id;
	}

	public static function currentSegment()
	{
		return Segments::current();
	}

	public static function instance()
	{
		$domain = Mapper::domain();
		return substr($domain, strpos($domain, '/') + 2);
	}

	public static function domain()
	{
		if ($instance = self::instance()) {
			return $instance;
		}
		
		$segment = self::currentSegment();

		if ($segment->domain) {
			return $segment->domain;
		}

		return Config::read('Sites.domain');
	}

	public static function url($path, $full = false)
	{
		return $full ? 'http://'.self::domain().$path : $path;
	}
}
