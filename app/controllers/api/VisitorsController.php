<?php

namespace app\controllers\api;

use lithium\storage\Session;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\entities\VisitorDevice;

class VisitorsController extends ApiController
{
	protected $skipBeforeFilter = ['requireVisitorAuth'];
	public function login() {
		$email = $this->request->get('data:email');
		$password = $this->request->get('data:password');

		$repository = new VisitorsRepository();
		$visitor = $repository->findByEmailAndPassword($email, $password);

		if ($visitor) {
			$device = $this->request->get('data:device');
			$device = new VisitorDevice([
				'id' => $device['id'],
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

	public function update() {
		$this->requireVisitorAuth();//log in visitor
		$currentPassword = $this->request->get('data:current_password');
		$newPassword = $this->request->get('data:password');
		$repository = new VisitorsRepository();
		$visitor = $repository->findByEmailAndPassword($this->visitor()->email(), $currentPassword);

		if ($visitor) {
			$visitor->setPassword($newPassword);
			$repository->update($visitor);
		} else {
			throw new ForbiddenException('Invalid visitor');
		}
	}
}
