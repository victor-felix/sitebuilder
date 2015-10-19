<?php
namespace meumobi\sitebuilder\services;

use Mailer;
use MeuMobi;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class SendForgottenUserPasswordMail
{
	public function send($user)
	{
		$segment = MeuMobi::currentSegment();

		$mailer = new Mailer([
			'from' => $segment->email,
			'to' => [$user->email => $user->fullname()],
			'subject' => s('users/mail/reset_password:subject', $segment->title),
			'views' => ['text/html' => 'users/forgot_password_mail.htm'],
			'layout' => 'mail',
			'data' => [
				'user' => $user,
				'site' => $user->site(),
				'segment' => $segment,
				'title' => s('[%s] Reset Password Request', $segment->title)
			]
		]);

		$mailer->send();
	}
}
