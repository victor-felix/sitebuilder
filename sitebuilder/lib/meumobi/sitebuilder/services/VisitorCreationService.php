<?php
namespace meumobi\sitebuilder\services;

class VisitorCreationService implements CreationService
{
	const RANDOM_PASSWORD = 'common';
	const DEFAULT_PASSWORD = 'default';

	public function create($data)
	{
		$this->applyPasswordStrategy($data['password_strategy'], $data);
		$visitor = new Visitor($data);
		$this->persistVisitor($visitor);	
		return $visitor;
	}

	protected function persistVisitor($visitor)
	{
		$repository = new VisitorsRepository();
		$repository->create($visitor);
	}

	protected function applyPasswordStrategy($strategy, &$data)
	{
		switch (@$data['password_strategy']) {
			case self::RANDOM_PASSWORD:
				$data['password'] = $this->randonPassword();
				$data['should_renew_password'] = true;
				break;
			case self::DEFAULT_PASSORD:
				$data['password'] = $this->defaultPassword($data['site_id']);
				$data['should_renew_password'] = true;
				break;
			case null:
				//not set, so do nothing
				break;
			default:
				throw new \Exception('Invalid password strategy');
		}
	}
	protected function randonPassword()
	{
		return \Security::randomPassword();
	}

	protected function defaultPassword($siteId)
	{
		return \Model::load('Sites')->firstById($siteId)->slug;
	}
}
