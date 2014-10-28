<?php

require dirname(__DIR__) . '/config/cli.php';

$_ = array_shift($argv);

$data = [
	'site_id' => 10,
	'plugin' => 'adtech',
	'options' => [
		'network' => '1502.1',
		'site-id' => '704333',
		'placement-id' => '5229219',
		'alias' => 'rglnr_mobile-5'
	]
];

$plugin = \app\models\Plugins::create($data);
$plugin->save();

print_r($plugin->to('array'));
