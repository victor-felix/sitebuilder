<?php

use meumobi\sitebuilder\WorkerManager;

require dirname(__DIR__) . '/config/cli.php';

meumobi_lock('perform_works', function() {
	while ($worker = WorkerManager::getNextJobWorker()) {
		WorkerManager::execute($worker);
	}
});
