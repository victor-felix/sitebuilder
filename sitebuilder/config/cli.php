<?php

require dirname(__DIR__) . '/config/bootstrap.php';

use meumobi\sitebuilder\Logger;
use Monolog\Handler\StreamHandler;
use Bramus\Monolog\Formatter\ColoredLineFormatter;

set_time_limit(0);

ini_set('display_errors', 'Off');

// logs to stdout for cli scripts
$formatter = new ColoredLineFormatter();
$handler = new StreamHandler('php://stdout', Config::read('Log.level'));
$handler->setFormatter($formatter);
Logger::logger()->pushHandler($handler);

function meumobi_lock($lock, $fn) {
	$pidpath = APP_ROOT . "/tmp/{$lock}.pid";
	$pidfile = fopen($pidpath, 'w+');
	$log = ['lock' => $lock];

	if (!flock($pidfile, LOCK_EX | LOCK_NB)) {
		Logger::notice('cli', 'cannot start cronjob. lock already acquired', $log);

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
