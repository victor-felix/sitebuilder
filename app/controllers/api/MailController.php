<?php

namespace app\controllers\api;

require_once 'lib/mailer/Mailer.php';

use Mailer;

class MailController extends ApiController
{
	public function index()
	{
		$this->requireUserAuth();

		$site = $this->site();
		$mailer = new Mailer(array(
			'from' => array($this->param('mail') => $this->param('name')),
			'to' => array($site->email => $site->title),
			'subject' => s('[MeuMobi] Contact Mail'),
			'views' => array('text/html' => 'sites/contact_mail.htm'),
			'layout' => 'mail',
			'data' => array(
				'name' => $this->param('name'),
				'mail' => $this->param('mail'),
				'phone' => $this->param('phone'),
				'message' => $this->param('message'),
			)
		));
		$mailer->send();
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
