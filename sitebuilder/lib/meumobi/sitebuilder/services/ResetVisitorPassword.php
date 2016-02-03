<?php

namespace meumobi\sitebuilder\services;

use Mailer;
use MeuMobi;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\services\VisitorPasswordGenerationService;

class ResetVisitorPassword
{
	const COMPONENT = 'reset_visitor_password';

	public function resetPassword($visitor)
	{
		$site = Model::load('Sites')->firstById($visitor->siteId());

		$passwordService = new VisitorPasswordGenerationService();
		$strategy = VisitorPasswordGenerationService::RANDOM_PASSWORD;
		$password = $passwordService->generate($visitor, $strategy, $site);
		$visitor->setPassword($password, true);

		$repository = new VisitorsRepository();
		$repository->update($visitor);

		$this->sendPasswordEmail($site, $visitor, $password);

		Logger::info(self::COMPONENT, "visitor's password reset", [
			'site_id' => $visitor->siteId(),
			'visitor_id' => $visitor->id(),
		]);

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
