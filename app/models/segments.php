<?php

class Segments
{
	protected $attr;

	public static function current()
	{
		return new self((array) Config::read('Segment'));
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

	public function enableMultiUsers()
	{
		if (array_key_exists('enableMultiUsers', $this->attr)) {
			return $this->attr['enableMultiUsers'];
		} else {
			return true;
		}
	}

	public function isEnabledFieldSet($fieldset)
	{
		if (array_key_exists('enableFieldSet', $this->attr)) {
			return in_array($fieldset, $this->attr['enableFieldSet']);
		} else {
			return true;
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

	public function themes()
	{
		if (array_key_exists('themes', $this->attr)) {
			return $this->attr['themes'];
		} else {
			return array();
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
