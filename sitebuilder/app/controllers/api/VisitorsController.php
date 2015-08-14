<?php

namespace app\controllers\api;

require_once 'lib/mailer/Mailer.php';

use DateTime;
use I18n;
use Mailer;
use MeuMobi;
use lithium\storage\Session;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\entities\VisitorDevice;
use meumobi\sitebuilder\presenters\api\VisitorPresenter;
use meumobi\sitebuilder\repositories\RecordNotFoundException;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\services\CreateOrUpdateDevice;
use meumobi\sitebuilder\services\ResetVisitorPassword;

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
		$deviceData = $this->request->get('data:device');

		$repository = new VisitorsRepository();
		$siteId = $this->site() ? $this->site()->id : null;
		$visitor = $repository->findForAuthentication($siteId, $email, $password);

		if ($visitor) {
			$errors = [];
			$response = [
				'success' => true,
			];

			if ($deviceData) {
				$service = new CreateOrUpdateDevice();
				list($created, $errors) = $service->perform([
					'data' => $deviceData,
					'visitor' => $visitor
				]);
			}

			$visitor->setLastLogin(new DateTime('NOW'));
			$repository->update($visitor);

			$response += [
				'token' => $visitor->authToken(),
				'visitor' => VisitorPresenter::present($visitor),
			];

			if ($visitor->shouldRenewPassword()) {
				$errors[] = 'password expired';
			}

			if ($errors) {
				$response['error'] = $errors[0];
				$response['errors'] = $errors;
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

	public function update_device()
	{
		$this->checkSite();
		$this->requireVisitorAuth();

		$repository = new VisitorsRepository();
		$visitor = $this->visitor();

		$deviceData = $this->request->data;
		$deviceData['uuid'] = $this->request->get('params:uuid');

		$service = new CreateOrUpdateDevice();
		$service->perform([
			'data' => $deviceData,
			'visitor' => $visitor
		]);

		$repository->update($visitor);

		return [ 'success' => true ];
	}

	public function forgot_password()
	{
		$email = $this->request->get('data:email');

		$repository = new VisitorsRepository();
		$service = new ResetVisitorPassword();

		$visitor = $repository->findByEmail($email);

		$service->resetPassword($visitor);

		return [ 'success' => true ];
	}
}
