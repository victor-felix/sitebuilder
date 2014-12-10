<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\services\ImportVisitorsCsvService;

require dirname(__DIR__) . '/config/cli.php';

$options = getopt('f:s:');

$site = Model::load('Sites')->firstById($options['s']);

require 'segments/' . $site->segment . '/config.php';

$import = new ImportVisitorsCsvService();

$import->setSite($site);
$import->setFile($options['f']);
echo $import->import();
