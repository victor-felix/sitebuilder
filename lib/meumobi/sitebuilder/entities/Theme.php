<?php

namespace meumobi\sitebuilder\entities;

use lithium\util\Inflector;
use meumobi\sitebuilder\repositories\SkinsRepository;

class Theme
{
	protected $id;
	protected $name;
	protected $assets;
	protected $colors;
	protected $thumbnails;

	public function __construct($attrs = [])
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
		return $this->id;
	}

	public function name()
	{
		return $this->name;
	}

	public function thumbnails()
	{
		return $this->thumbnails;
	}

	public function skins()
	{
		$skinsRepo = new SkinsRepository();
		return $skinsRepo->findByThemeId($this->id);
	}
}
