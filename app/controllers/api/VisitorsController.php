<?php

namespace app\controllers\api;

use lithium\storage\Session;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

class VisitorsController extends ApiController
{
	protected $skipBeforeFilter = ['requireVisitorAuth'];
	public function login() {
		$email = $this->request->get('data:email');
		$password = $this->request->get('data:password');

		$repository = new VisitorsRepository();
		$visitor = $repository->findByEmailAndPassword($email, $password);

		if ($visitor) {
			// $visitor->addDevice($request->params->device_id, $request->params->model);// add device to visitor if needed
			Session::write(
				\Auth::SESSION_KEY,
				serialize($visitor)
			);
		} else {
			throw new UnAuthorizedException('Invalid visitor');
		}
	}
}
