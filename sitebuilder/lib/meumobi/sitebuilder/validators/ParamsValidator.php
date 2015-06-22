<?php

namespace meumobi\sitebuilder\validators;

use Exception;

class ParamsValidator
{
	public static function validate($options, $validOptions)
	{
		$invalidOptions = array_diff(array_keys($options), $validOptions);

		if ($invalidOptions) {
			throw new Exception('invalid options: ' . implode(', ', $invalidOptions));
		}

		return array_map(function($option) use ($options) {
			return isset($options[$option]) ? $options[$option] : null;
		}, $validOptions);
	}
}
