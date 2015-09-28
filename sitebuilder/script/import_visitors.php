<?php

use meumobi\sitebuilder\services\ImportVisitorsCsvService;
use meumobi\sitebuilder\services\VisitorPasswordGenerationService;

require dirname(__DIR__) . '/config/cli.php';

$options = getopt(null, ['site:', 'import-file:', 'auto-password', 'exclusive', 'resend-invite']);

//TODO use ParamsValidator after it be available on master
if (isset($options['import-file']) && isset($options['site'])) {
	$site = Model::load('Sites')->firstById($options['site']);

	loadSegment($site->segment);

	$options['resend'] = isset($options['resend-invite']);
	$import = new ImportVisitorsCsvService();
	if (isset($options['exclusive'])) $import->setMethod(ImportVisitorsCsvService::EXCLUSIVE);
	if (isset($options['auto-password'])) $import->setPasswordStrategy(VisitorPasswordGenerationService::RANDOM_PASSWORD);
	$import->setSite($site);
	$import->setFile($options['import-file']);
	echo $import->import($options);
} else {
	echo <<<'EOL'
usage: php import_visitors.php OPTIONS
import site visitors from a csv file
OPTIONS:
	MANDATORY:
		--import-file: csv file path
		--site: site id
	OPTIONAL:
		--password-strategy default|random: if default the site slug will be used as password
		--exclusive: removes previous visitors before import
		--resend: send invite email to all imported visitors, including the already created ones
EXAMPLE:
$php sitebuilder/script/import_visitors.php --resend --exclusive --site 234 --import-file tmp/visitors.csv --password-strategy random

EOL;
}
