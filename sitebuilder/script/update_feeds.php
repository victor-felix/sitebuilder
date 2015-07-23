<?php

require dirname(__DIR__) . '/config/cli.php';

use app\models\Extensions;
use meumobi\sitebuilder\workers\UpdateFeedsWorker;

$priorities = [
	'high' => Extensions::PRIORITY_HIGH,
	'low' => Extensions::PRIORITY_LOW
];

$priority = $priorities[$argv[1]];

meumobi_lock("update_feeds_{$argv[1]}", function() use ($priority) {
	$worker = new UpdateFeedsWorker();
	$worker->perform(compact('priority'));
});
