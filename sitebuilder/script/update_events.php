<?php

use meumobi\sitebuilder\services\UpdateEventsService;

require dirname(__DIR__) . '/config/cli.php';

function get($product, $key) {
	$value = $product->xpath($key);

	if (isset($value[0])) {
		return (string) $value[0];
	} else {
		return null;
	}
}

$priorities = ['high' => UpdateEventsService::PRIORITY_HIGH,
	'low' => UpdateEventsService::PRIORITY_LOW];
$priority = $priorities[$argv[1]];

meumobi_lock("update_events_{$argv[1]}", function() use ($priority) {
	$service = new UpdateEventsService([
		'priority' => $priority,
		'logger_path' => 'log/events.log'
	]);
	$service->call();
});
