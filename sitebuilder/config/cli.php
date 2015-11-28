<?php

require dirname(__DIR__) . '/config/bootstrap.php';

use meumobi\sitebuilder\Logger;

set_time_limit(0);

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');

function meumobi_lock($lock, $fn) {
	$pidpath = APP_ROOT . "/tmp/{$lock}.pid";
	$pidfile = fopen($pidpath, 'w+');
	$log = ['lock' => $lock];

	if (!flock($pidfile, LOCK_EX | LOCK_NB)) {
		Logger::info('cli', 'cannot start cronjob. lock already acquired', $log);

		exit();
	}

	Logger::info('cli', 'starting cronjob', $log);

	fwrite($pidfile, getmypid());
	fflush($pidfile);

	$fn();

	fclose($pidfile);
	unlink($pidpath);

	Logger::info('cli', 'cronjob finished', $log);
}
