<?php

namespace app\controllers\api;

use lithium\storage\Session;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\entities\VisitorDevice;
use meumobi\sitebuilder\presenters\api\VisitorPresenter;

class VisitorsController extends ApiController
{
	protected $skipBeforeFilter = ['requireVisitorAuth', 'checkSite'];

	public function login_without_site()
	{
		return $this->loginUser();
	}

	public function login()
	{
		$this->checkSite();

		return $this->loginUser();
	}

	protected function loginUser()
	{
		$email = $this->request->get('data:email');
		$password = $this->request->get('data:password');

		$repository = new VisitorsRepository();
		$siteId = $this->site() ? $this->site()->id : null;
		$visitor = $repository->findForAuthentication($siteId, $email, $password);

		if ($visitor) {
			$deviceData = $this->request->get('data:device');
			if ($deviceData) {
				$device = new VisitorDevice($deviceData);
				$visitor->addDevice($device);
			}
			$visitor->setLastLogin(date('Y-m-d H:i:s'));
			$repository->update($visitor);

			$response = [
				'success' => true,
				'token' => $visitor->authToken(),
				'visitor' => VisitorPresenter::present($visitor),
			];

			if ($visitor->shouldRenewPassword()) {
				$response['error'] = 'password expired';
			}

			return $response;
		} else {
			throw new UnAuthorizedException('invalid visitor');
		}
	}

	public function show()
	{
		$this->checkSite();
		$this->requireVisitorAuth();

		return VisitorPresenter::present($this->visitor());
	}

	public function update()
	{
		$this->checkSite();
		$this->requireVisitorAuth(['allowExpired' => true]);

		$currentPassword = $this->request->get('data:current_password');
		$newPassword = $this->request->get('data:password');
		$repository = new VisitorsRepository();
		$visitor = $this->visitor();

		if ($visitor && $visitor->passwordMatch($currentPassword)) {
			$visitor->setPassword($newPassword);
			$repository->update($visitor);
			return [
				'success' => true,
				'token' => $visitor->authToken(),
				'visitor' => VisitorPresenter::present($visitor)
			];
		} else {
			throw new ForbiddenException('Invalid visitor');
		}
	}

	public function add_device()
	{
		$this->checkSite();
		$this->requireVisitorAuth();

		$repository = new VisitorsRepository();
		$visitor = $this->visitor();
		$device = new VisitorDevice([
			'uuid' => $this->request->get('data:uuid'),
			'pushId' => $this->request->get('data:push_id'),
			'model' => $this->request->get('data:model'),
			'app_version' => $this->request->get('data:app_version')
		]);
		$visitor->addDevice($device);
		$repository->update($visitor);

		return [ 'success' => true ];
	}

	public function update_device()
	{
		$this->checkSite();
		$this->requireVisitorAuth();

		$repository = new VisitorsRepository();
		$visitor = $this->visitor();
		$device = $visitor->findDevice($this->request->get('params:device_id'));
		$device->update($this->request->data);
		$repository->update($visitor);

		return [ 'success' => true ];
	}
}
