<?php

class Segments
{
	protected $attr;

	public static function firstById($id)
	{
		$segments = Config::read('Segments');
		return new self($segments[$id]);
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
		return $this->enableSignUp;
	}

	public function sitePreviewUrl()
	{
		if (array_key_exists('sitePreviewUrl', $this->attr)) {
			return $this->attr['sitePreviewUrl'];
		} else {
			return Config::read('Preview.url');
		}
	}

	public static function listItemTypesFor($segment) {
		$segment = self::firstById($segment);
		$types = (array) $segment->items;
		$type_list = array();

		foreach($types as $type) {
			$title = Inflector::humanize($type);
			$type_list[$type] = $title;
		}

		return $type_list;
	}
}
