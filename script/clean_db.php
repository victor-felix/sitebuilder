<?php

require dirname(__DIR__) . '/config/bootstrap.php';

set_time_limit(60 * 20);

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

$_ = array_shift($argv);

$Users = Model::load('Users');
$users = $Users->allByName($argv[0]);

foreach ($users as $user) {
	try {
		$Users->delete($user->id);
	} catch (Exception $e) {
		echo $e;
	}
}
