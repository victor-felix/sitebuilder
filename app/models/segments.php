<?php

class Segments
{
	protected $attr;

	public static function current()
	{
		return new self(Config::read('Segment'));
	}

	public function __construct($attr)
	{
		$this->attr = $attr;
	}

	public function __get($key)
	{
		if (array_key_exists($key, $this->attr)) {
			return $this->attr[$key];
		}
	}

	public function isSignupEnabled()
	{
		return $this->enableSignup;
	}

	public function sitePreviewUrl()
	{
		if (array_key_exists('sitePreviewUrl', $this->attr)) {
			return $this->attr['sitePreviewUrl'];
		} else {
			return Config::read('Preview.url');
		}
	}

	public function fullOptions()
	{
		if (array_key_exists('fullOptions', $this->attr)) {
			return $this->attr['fullOptions'];
		} else {
			return true;
		}
	}

	public function themeTag()
	{
		if (Config::read('Themes.ignoreTag')) return false;

		if (array_key_exists('themeTag', $this->attr)) {
			return $this->attr['themeTag'];
		} else {
			return $this->id;
		}
	}

	public static function listItemTypesFor($segment) {
		$segment = self::current();
		$types = (array) $segment->items;
		$type_list = array();

		foreach($types as $type) {
			$title = Inflector::humanize($type);
			$type_list[$type] = $title;
		}

		return $type_list;
	}
}
