<?php

namespace meumobi\sitebuilder\services;

use Mailer;
use MeuMobi;
use Model;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\services\VisitorPasswordGenerationService;

class ResetVisitorPassword
{
	public function resetPassword($visitor)
	{
		$repository = new VisitorsRepository();
		$passwordService = new VisitorPasswordGenerationService();
		$strategy = VisitorPasswordGenerationService::RANDOM_PASSWORD;

		$site = Model::load('Sites')->firstById($visitor->siteId());

		$password = $passwordService->generate($visitor, $strategy, $site);
		$visitor->setPassword($password);

		$repository->update($visitor);
		$this->sendPasswordEmail($site, $visitor, $password);

		return true;
	}

	protected function sendPasswordEmail($site, $visitor, $password)
	{
		$segment = MeuMobi::currentSegment();

		$mailer = new Mailer([
			'from' => $segment->email,
			'to' => $visitor->email(),
			'subject' => s('[%s]: New password', $site->title),
			'views' => ['text/html' => 'visitors/forgot_password_mail.htm'],
			'layout' => 'mail',
			'data' =>  [
				'segment' => $segment,
				'password' => $password,
				'email' => $visitor->email(),
				'visitor' => $visitor,
				'site' => $site,
			],
		]);

		$mailer->send();
	}
}
