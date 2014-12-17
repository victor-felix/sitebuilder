<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use app\presenters\VisitorsArrayPresenter;

require dirname(__DIR__) . '/config/cli.php';

$options = getopt('o:s:');

$siteId = $options['s'];
$repo = new VisitorsRepository();
$visitors = $repo->findBySiteId($siteId);
$presenter = new VisitorsArrayPresenter($visitors);

if ((file_put_contents($options['o'], $presenter->toCSV())))
	echo "exported\n ";
