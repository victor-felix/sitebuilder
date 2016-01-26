<?php

require dirname(__DIR__) . '/config/cli.php';

$options = [];

$str = preg_replace('/--/', '', implode('&', array_slice($argv, 1)));
parse_str($str, $options);

if (!isset($options['worker'])) {
	die('you need --worker for this to work!');
}

$workerName = array_unset($options, 'worker');
$lock = isset($options['lock'])
	? $options['lock']
	: $workerName . '-' . uniqid();

meumobi_lock($lock, function() use ($workerName, $options) {
	$workerClass = 'meumobi\sitebuilder\workers\\' . $workerName . 'Worker';
	$worker = new $workerClass([ 'params' => $options ]);
	$worker->perform();
});
