<?php

require dirname(__DIR__) . '/config/bootstrap.php';
require dirname(__DIR__) . '/config/error_handler.php';

set_time_limit(0);

ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

function meumobi_lock($lock, $fn) {
	$pidpath = APP_ROOT . "/tmp/{$lock}.pid";
	$pidfile = fopen($pidpath, 'w+');

	if (!flock($pidfile, LOCK_EX | LOCK_NB)) exit();

	fwrite($pidfile, getmypid());
	fflush($pidfile);

	$fn();

	fclose($pidfile);
	unlink($pidpath);
}
