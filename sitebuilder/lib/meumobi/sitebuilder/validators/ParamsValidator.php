<?php

namespace meumobi\sitebuilder\validators;

use Exception;

class ParamsValidator
{
	public static function validate($options, $validOptions, $strict = true)
	{
		$invalidOptions = array_diff(array_keys($options), $validOptions);

		if ($strict && $invalidOptions) {
			throw new Exception('invalid options: ' . implode(', ', $invalidOptions));
		}

		return array_map(function($option) use ($options) {
			return isset($options[$option]) ? $options[$option] : null;
		}, $validOptions);
	}
}
