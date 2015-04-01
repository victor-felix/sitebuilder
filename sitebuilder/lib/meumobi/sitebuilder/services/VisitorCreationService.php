<?php
namespace meumobi\sitebuilder\services;

class VisitorCreationService implements CreationService
{
	const RANDOM_PASSWORD = 'common';
	const DEFAULT_PASSWORD = 'default';

	public function create($data)
	{
		switch (@$data['password_strategy']) {
			case self::RANDOM_PASSWORD:
				break;
			case self::DEFAULT_PASSORD:
				break;
			default:
				throw new \Exception('Invalid password strategy');
		}	
	}

	protected function randonPassword()
	{
	
	}

	protected function defaultPassword()
	{
	
	}
}
