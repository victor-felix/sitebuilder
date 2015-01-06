<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

require dirname(__DIR__) . '/config/cli.php';

function updateVisitorPassword($email)
{
	$repository = new VisitorsRepository();
	$visitor = $repository->findByEmail($email);
	$password = \Security::randomPassword();
	$visitor->setPassword($password);
	$repository->update($visitor);
	echo "Visitor password updated to: $password\n";
	sendVisitorEmail($visitor, $password);
}

function sendVisitorEmail($visitor, $password)
{
	$site = Model::load('Sites')->firstById($visitor->siteId());
	\I18n::locale($site->language);
	$data =	[
		'title' => s('[%s]: New password', $site->title),
		'segment' => \MeuMobi::currentSegment(),
		'password' => $password,
		'visitor' => $visitor,
		'site' => $site,
	];
	$mailer = new \Mailer(array(
		'from' => $data['segment']->email,
		'to' => $visitor->email(),
		'subject' => $data['title'],
		'views' => array('text/html' => 'visitors/forgot_password_mail.htm'),
		'layout' => 'mail',
		'data' =>  $data,
	));
	echo "sending email to : {$visitor->email()}\n";
	return $mailer->send();
}

$options = getopt('', ['email:']);

if (isset($options['email'])) updateVisitorPassword($options['email']);
