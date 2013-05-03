<?php

namespace meumobi\sitebuilder\presenters\api;

class SkinPresenter
{
	public static function present($object)
	{
		return [
			'id' => $object->id(),
			'theme_id' => $object->themeId(),
			'parent_id' => $object->parentId(),
			'colors' => $object->colors(),
			'assets' => $object->assets()
		];
	}

	public static function presentSet($set)
	{
		return array_map(array(__CLASS__, 'present'), $set);
	}
}
