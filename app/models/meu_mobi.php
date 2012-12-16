<?php

require_once 'app/models/segments.php';

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

	public static function currentSegment()
	{
		return Segments::firstById(static::$segment);
	}

	public static function instance()
	{
		$domain = Mapper::domain();
		return substr($domain, strpos($domain, '/') + 2);
	}

	public static function domain()
	{
		$segment = Model::load('Segments')->firstById(static::segment());
		$domain = null;

		if (property_exists($segment, 'domain')) {
			$domain = $segment->domain;
		}

		if (!$domain) {
			$domain = Config::read('Sites.domain');
		}

		if (!$domain) {
			$domain = self::instance();
			$domain = substr($domain, strpos($domain, '.') + 1);
		}

		return $domain;
	}
}
