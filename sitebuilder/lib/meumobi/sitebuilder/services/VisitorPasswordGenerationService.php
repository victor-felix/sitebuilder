<?php
namespace meumobi\sitebuilder\services;

class VisitorPasswordGenerationService
{
	const RANDOM_PASSWORD = 'random';
	const DEFAULT_PASSWORD = 'default';

	public function generate($visitor, $strategy, $site)
	{
		$password = null;
		switch ($strategy) {
			case self::RANDOM_PASSWORD:
				$password = $this->randonPassword();
				$visitor->setPassword($password, true);
				break;
			case self::DEFAULT_PASSWORD:
				$password = $this->defaultPassword($site);
				$visitor->setPassword($password, true);
				break;
			default:
				throw new \Exception('Invalid password strategy');
		}
		return $password;
	}

	protected function randonPassword()
	{
		return \Security::randomPassword();
	}

	protected function defaultPassword($site)
	{
		return $site->slug;
	}
}
