<?php

require dirname(__DIR__) . '/config/bootstrap.php';

use meumobi\sitebuilder\Logger;
use Monolog\Handler\StreamHandler;
use Bramus\Monolog\Formatter\ColoredLineFormatter;

set_time_limit(0);
ini_set('display_errors', 'Off');

define('COMPONENT', 'cli');

// logs to stdout for cli scripts
$formatter = new ColoredLineFormatter();
$handler = new StreamHandler('php://stdout', Config::read('Log.level'));
$handler->setFormatter($formatter);
Logger::logger()->pushHandler($handler);

function meumobi_lock($script_id, $fn) {
	$pidpath = APP_ROOT . "/tmp/{$script_id}.pid";
	$pidfile = fopen($pidpath, 'w+');
	$log = ['script_id' => $script_id];

	if (!flock($pidfile, LOCK_EX | LOCK_NB)) {
		Logger::notice(COMPONENT, 'cannot start script. lock already acquired', $log);

		exit();
	}

	Logger::info(COMPONENT, 'starting script', $log);

	fwrite($pidfile, getmypid());
	fflush($pidfile);

	$fn();

	fclose($pidfile);
	unlink($pidpath);

	Logger::info(COMPONENT, 'script finished', $log);
}
