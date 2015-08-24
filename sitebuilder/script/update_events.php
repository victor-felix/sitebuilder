<?php

require dirname(__DIR__) . '/config/cli.php';

use app\models\Extensions;
use meumobi\sitebuilder\workers\UpdateEventsFeedWorker;

$priorities = [
	'high' => Extensions::PRIORITY_HIGH,
	'low' => Extensions::PRIORITY_LOW
];

$priority = $priorities[$argv[1]];

meumobi_lock("update_events_{$argv[1]}", function() use ($priority) {
	$worker = new UpdateEventsFeedWorker();
	$worker->perform(compact('priority'));
});
