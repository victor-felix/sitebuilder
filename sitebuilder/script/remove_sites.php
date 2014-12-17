<?php

require dirname(__DIR__) . '/config/cli.php';

$_ = array_shift($argv);

$Sites = Model::load('Sites');

foreach ($argv as $site_id) {
	try {
		$Sites->delete($site_id);
	} catch (Exception $e) {
		echo $e;
	}
}
