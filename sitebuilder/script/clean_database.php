<?php

require dirname(__DIR__) . '/config/cli.php';

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
