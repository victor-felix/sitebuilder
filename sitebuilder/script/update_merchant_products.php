<?php

use meumobi\sitebuilder\services\UpdateMerchantProductsService;

require dirname(__DIR__) . '/config/cli.php';

function get($product, $key) {
	$value = $product->xpath($key);

	if (isset($value[0])) {
		return (string) $value[0];
	} else {
		return null;
	}
}

$priorities = ['high' => UpdateMerchantProductsService::PRIORITY_HIGH,
	'low' => UpdateMerchantProductsService::PRIORITY_LOW];
$priority = $priorities[$argv[1]];

meumobi_lock("update_merchant_products_{$argv[1]}", function() use ($priority) {
	$service = new UpdateMerchantProductsService([
		'priority' => $priority,
		'logger_path' => 'log/merchant_products.log'
	]);
	$service->call();
});
