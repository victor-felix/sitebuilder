<?php

namespace meumobi\sitebuilder\presenters\api;

class VisitorPresenter
{
	public static function present($object)
	{
		return [
			'first_name' => $object->firstName(),
			'last_name' => $object->lastName(),
			'email' => $object->email(),
		];
	}

	public static function presentSet($set)
	{
		return array_map(array(__CLASS__, 'present'), $set);
	}
}
