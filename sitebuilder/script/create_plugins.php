<?php

require dirname(__DIR__) . '/config/cli.php';

$_ = array_shift($argv);

$file = @$argv[0];

if (!$file) {
	echo <<<EOL
	File format:
	[
		{
			"site_id" : 10,
			"plugin" : "adtech",
			"options" : {
				"network" : "1502.1",
				"site_id" : "704333",
				"placement_id" : "5229219",
				"alias" : "rglnr_mobile-5"
			}
		},
	...
	]

EOL;
	return;
}

$json = file_get_contents($file);
$plugins = json_decode($json, 1);

foreach ($plugins as $data) {
	$plugin = \app\models\Plugins::create($data);
	$plugin->save();
	print_r($plugin->to('array'));
}
