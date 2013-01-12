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

	public function themeTag()
	{
		if (array_key_exists('themeTag', $this->themeTag)) {
			return $this->attr['themeTag'];
		} else if(!Config::read('Themes.ignoreTag')) {
			return $this->id;
		} else {
			return false;
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
