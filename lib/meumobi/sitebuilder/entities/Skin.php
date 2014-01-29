<?php

namespace meumobi\sitebuilder\entities;

use lithium\util\Inflector;

use MongoId;
use FileUpload;

class Skin
{
	protected $id;
	protected $themeId;
	protected $parentId;
	protected $mainColor;
	protected $assets = array();
	protected $uploadedAssets = array();
	protected $colors = array();
	protected $html5;

	public function __construct(array $attrs = [])
	{
		$this->setAttributes($attrs);
	}

	public function setAttributes(array $attrs)
	{
		foreach ($attrs as $key => $value) {
			$key = Inflector::camelize($key, false);
			$method = 'set' . Inflector::camelize($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			} else if (property_exists($this, $key)) {
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

	public function html5()
	{
		return $this->html5;
	}

	public function uploadedAssets()
	{
		return $this->uploadedAssets;
	}

	public function setAsset($asset, $value)
	{
		$this->assets[$asset] = $value;
	}

	public function setUploadedAssets($file)
	{
		$files = array();

		foreach ($file as $key => $assets) {
			foreach ($assets as $asset => $value) {
				$files[$asset][$key] = $value;
			}
		}

		$this->uploadedAssets = array_filter($files, function($asset) {
			if ($asset['error']) return false;
			list($valid, $errors) = FileUpload::validate($asset, null, ['png',
				'jpeg', 'jpg', 'gif']);
			return $valid;
		});
	}
}
