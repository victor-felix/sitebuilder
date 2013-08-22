<?php

use meumobi\sitebuilder\services\UpdateFeedsService;

require dirname(__DIR__) . '/config/cli.php';

$priorities = ['high' => UpdateFeedsService::PRIORITY_HIGH,
	'low' => UpdateFeedsService::PRIORITY_LOW];
$priority = $priorities[$argv[1]];

meumobi_lock("update_feeds_{$priority}", function() use ($priority) {
	$service = new UpdateFeedsService([
		'priority' => $priority,
		'logger_path' => 'log/feeds.log'
	]);
	$service->call();
});
