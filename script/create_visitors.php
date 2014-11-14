<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

require dirname(__DIR__) . '/config/cli.php';

$_ = array_shift($argv);

$file = $argv[0];
$json = file_get_contents($file);
$visitors = json_decode($json, 1);
$repo = new VisitorsRepository();

foreach ($visitors as $visitor) {
	$visitor = new Visitor($visitor);
	$repo->create($visitor);
	echo "Id: {$visitor->id()} \n";
}
