<?php

namespace meumobi\sitebuilder\entities;

use lithium\util\Inflector;

class Skin
{
	protected $id;
	protected $themeId;
	protected $mainColor;
	protected $assets;
	protected $colors;

	public function __construct($attrs = array())
	{
		foreach ($attrs as $key => $value) {
			$key = Inflector::camelize($key, false);
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	public function id()
	{
		return $this->id->{'$id'};
	}

	public function themeId()
	{
		return $this->themeId;
	}

	public function mainColor()
	{
		return $this->mainColor;
	}

	public function colors()
	{
		return $this->colors;
	}

	public function assets()
	{
		return $this->assets;
	}
}
