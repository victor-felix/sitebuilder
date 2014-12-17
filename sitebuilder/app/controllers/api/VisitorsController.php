<?php

namespace app\controllers\api;

use lithium\storage\Session;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\entities\VisitorDevice;

class VisitorsController extends ApiController
{
	protected $skipBeforeFilter = ['requireVisitorAuth'];

	public function login()
	{
		$email = $this->request->get('data:email');
		$password = $this->request->get('data:password');

		$repository = new VisitorsRepository();
		$visitor = $repository->findForAuthentication($this->site()->id, $email, $password);

		if ($visitor) {
			$device = $this->request->get('data:device');
			$device = new VisitorDevice([
				'uuid' => $device['uuid'],
				'pushId' => $device['pushId'],
				'model' => $device['model']
			]);
			$visitor->addDevice($device);
			$visitor->setLastLogin(date('Y-m-d H:i:s'));
			$repository->update($visitor);
			return [ 'token' => $visitor->authToken() ];
		} else {
			throw new UnAuthorizedException('Invalid visitor');
		}
	}

	public function update()
	{
		$this->requireVisitorAuth();
		$currentPassword = $this->request->get('data:current_password');
		$newPassword = $this->request->get('data:password');
		$repository = new VisitorsRepository();
		$visitor = $this->visitor();

		if ($visitor && $visitor->passwordMatch($currentPassword)) {
			$visitor->setPassword($newPassword);
			$repository->update($visitor);
			return [ 'success' => true ];
		} else {
			throw new ForbiddenException('Invalid visitor');
		}
	}

	public function addDevice()
	{
		$this->requireVisitorAuth();
		$repository = new VisitorsRepository();
		$visitor = $this->visitor();
		$device = new VisitorDevice([
			'uuid' => $this->request->get('data:uuid'),
			'pushId' => $this->request->get('data:pushId'),
			'model' => $this->request->get('data:model')
		]);
		$visitor->addDevice($device);
		$repository->update($visitor);
	}
}
