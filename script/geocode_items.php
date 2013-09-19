<?php
use meumobi\sitebuilder\services\GeocodeItemsService;

require dirname(__DIR__) . '/config/cli.php';

$priorities = [
	'high' => GeocodeItemsService::PRIORITY_HIGH,
	'low' => GeocodeItemsService::PRIORITY_LOW
];
$priority = $priorities[$argv[1]];

meumobi_lock("geocode_items_{$argv[1]}", function() use ($priority) {
	$service = new GeocodeItemsService([
			'priority' => $priority,
			'logger_path' => 'log/geocodes.log'
			]);
	$service->call();
});
