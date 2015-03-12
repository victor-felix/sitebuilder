<?php

use meumobi\sitebuilder\services\ImportItemsCsvService;

require dirname(__DIR__) . '/config/cli.php';

meumobi_lock("import_csv", function() {
	$service = new ImportItemsCsvService();
	$service->call();
});
