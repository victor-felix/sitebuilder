<?php
use meumobi\sitebuilder\workers\UpdateFeedsWorker;
use meumobi\sitebuilder\Logger;
require dirname(__DIR__) . '/config/cli.php';

$priorities = [
	'high' => UpdateFeedsWorker::PRIORITY_HIGH,
	'low' => UpdateFeedsWorker::PRIORITY_LOW
];

$priority = $priorities[$argv[1]];

meumobi_lock("update_feeds_{$argv[1]}", function() use ($priority) {
	$worker = new UpdateFeedsWorker([
		'priority' => $priority,
		'logger' => Logger::logger()
	]);
	$worker->perform();
});
