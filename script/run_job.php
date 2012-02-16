<?php
require dirname(dirname(__DIR__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';

$filename = array_shift($argv);
$type = array_shift($argv);
$classname = 'lib/utils/Jobs/';

switch ($type) {
	case 'import':
		$class = 'Import';
		require_once $classname . $class . '.php';
		break;
	case 'geocode':
		$class = 'Geocode';
		require_once $classname . $class . '.php';
			break;
	default:
		return;
}

echo $class::Create()->start();
