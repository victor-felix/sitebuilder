<?php
use meumobi\sitebuilder\services\ImportCsvService;

require dirname(__DIR__) . '/config/cli.php';

meumobi_lock("import_csv", function() {
	$service = new ImportCsvService(['logger_path' => 'log/imports.log']);
	$service->call();
});