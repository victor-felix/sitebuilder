<?php

namespace meumobi\sitebuilder\services;

use Exception;
use Security;

class VisitorPasswordGenerationService
{
	const RANDOM_PASSWORD = 'random';
	const DEFAULT_PASSWORD = 'default';

	public function generate($visitor, $strategy, $site)
	{
		switch ($strategy) {
			case self::RANDOM_PASSWORD:
				$password = $this->randomPassword();
				$visitor->setPassword($password, true);
				return $password;
			case self::DEFAULT_PASSWORD:
				$password = $this->defaultPassword($site);
				$visitor->setPassword($password, true);
				return $password;
			default:
				throw new Exception('invalid password strategy');
		}
	}

	protected function randomPassword()
	{
		return Security::randomPassword();
	}

	protected function defaultPassword($site)
	{
		return $site->slug;
	}
}
