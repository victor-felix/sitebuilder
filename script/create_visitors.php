<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

require dirname(__DIR__) . '/config/cli.php';

$_ = array_shift($argv);

$file = @$argv[0];

if (!$file) {
	echo <<<EOL
	File format:
	[
		{
			"site_id" : 10, 
			"email" : "visitor@mail.com",
			"password" : "123456",
			"groups" : ["group1", "group2"],
			"devices" : []
		},
	...
	]	

EOL;
	return;
}

$json = file_get_contents($file);
$visitors = json_decode($json, 1);
$repo = new VisitorsRepository();

foreach ($visitors as $visitor) {
	$visitor = new Visitor($visitor);
	$repo->create($visitor);
	echo "Id: {$visitor->id()} \n";
}
