<?php

require dirname(__DIR__) . '/config/bootstrap.php';

set_time_limit(0);

ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 'On');

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
