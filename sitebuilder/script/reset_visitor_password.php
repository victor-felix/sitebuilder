<?php
require dirname(__DIR__) . '/config/cli.php';

use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\services\ResetVisitorPassword;

function updateVisitorPassword($email)
{
	$visitor = getVisitor($email);
	$service = new ResetVisitorPassword();

	$site = Model::load('Sites')->firstById($visitor->siteId());
	loadSegment($site->segment);
	I18n::locale($site->language);

	if ($service->resetPassword($visitor)) {
		echo "Visitor password successfully updated.\n";
	}

}

function getVisitor($email)
{
	$repository = new VisitorsRepository();
	$visitor = $repository->findByEmail($email);

	if (!$visitor) {
		exit("invalid visitor.\n");
	}

	return $visitor;
}

$options = getopt('', ['email:']);

if (isset($options['email'])) {
	updateVisitorPassword($options['email']);
} else {
	echo <<<'EOL'
	usage: php import_visitors.php OPTIONS

	reset visitor password

	OPTIONS::
	MANDATORY :
		--email visitor email

	EXAMPLE:
	$php sitebuilder/script/reset_visitor_password.php --email example@example.com

EOL;
}
