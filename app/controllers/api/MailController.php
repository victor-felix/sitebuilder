<?php

namespace app\controllers\api;

require_once 'lib/mailer/Mailer.php';

use Mailer;

class MailController extends ApiController
{
	public function index()
	{
		$this->requireUserAuth();

		if (!isset($this->request->data['name']) &&
			!isset($this->request->data['mail']) &&
			!isset($this->request->data['message']) {
			return array('error' => 'missing parameters');
		}

		$site = $this->site();
		$mailer = new Mailer(array(
			'from' => array($this->request->get('data:mail') => $this->request->get('data:name')),
			'to' => array($site->email => $site->title),
			'subject' => s('[MeuMobi] Contact Mail'),
			'views' => array('text/html' => 'sites/contact_mail.htm'),
			'layout' => 'mail',
			'data' => array(
				'name' => $this->request->get('data:name'),
				'mail' => $this->request->get('data:mail'),
				'phone' => $this->request->get('data:phone'),
				'message' => $this->request->get('data:message'),
			)
		));
		$mailer->send();

		return array('success' => true);
	}

	protected function requireUserAuth()
	{
		if (\Config::read('Api.ignoreAuth')) return;

		$token = $this->request->env('HTTP_X_AUTHENTICATION_TOKEN');

		if ($token != '9456bbf53af6fdf30a5d625ebf155b4018c8b0aephp') {
			throw new NotAuthenticatedException('authentication required');
		}
	}
}
