<?php

require dirname(__DIR__) . '/config/cli.php';

use meumobi\sitebuilder\services\RemoveSite;

$_ = array_shift($argv);

$model = Model::load('Sites');

$service = new RemoveSite();

foreach ($argv as $site_id) {
	try {
		$site = $model->firstById($site_id);
		$service->remove($site);
	} catch (Exception $e) {
		echo $e;
	}
}
