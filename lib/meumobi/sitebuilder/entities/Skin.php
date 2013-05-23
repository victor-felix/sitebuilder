<?php

namespace meumobi\sitebuilder\entities;

use lithium\util\Inflector;
use MongoId;

class Skin
{
	protected $id;
	protected $themeId;
	protected $parentId;
	protected $mainColor;
	protected $assets;
	protected $colors;

	public function __construct(array $attrs = array())
	{
		$this->setAttributes($attrs);
	}

	public function setAttributes(array $attrs)
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
		return $this->id ? $this->id->{'$id'} : null;
	}

	public function setId(MongoId $id)
	{
		$this->id = $id;
	}

	public function themeId()
	{
		return $this->themeId;
	}

	public function parentId()
	{
		return $this->parentId;
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
