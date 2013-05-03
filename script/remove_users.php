<?php

require dirname(__DIR__) . '/config/bootstrap.php';

set_time_limit(60 * 20);

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

$_ = array_shift($argv);

$Users = Model::load('Users');

foreach ($argv as $user_id) {
	try {
		$Users->delete($user_id);
	} catch (Exception $e) {
		echo $e;
	}
}
