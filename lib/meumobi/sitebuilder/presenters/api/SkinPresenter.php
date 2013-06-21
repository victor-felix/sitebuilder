<?php

namespace meumobi\sitebuilder\presenters\api;

use Mapper;

class SkinPresenter
{
	public static function present($object)
	{
		return [
			'id' => $object->id(),
			'theme_id' => $object->themeId(),
			'parent_id' => $object->parentId(),
			'colors' => $object->colors(),
			'assets' => self::decorateAssets($object->assets())
		];
	}

	public static function presentSet($set)
	{
		return array_map(array(__CLASS__, 'present'), $set);
	}

	protected static function decorateAssets($assets)
	{
		$decoratedAssets = [];

		foreach ($assets as $key => $value) {
			if ($value) {
				$decoratedAssets[$key] = Mapper::url($value, true);
			}
		}

		return $decoratedAssets;
	}
}
