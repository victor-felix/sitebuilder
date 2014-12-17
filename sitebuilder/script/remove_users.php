<?php

require dirname(__DIR__) . '/config/cli.php';

$_ = array_shift($argv);

$Users = Model::load('Users');

foreach ($argv as $user_id) {
	try {
		$Users->delete($user_id);
	} catch (Exception $e) {
		echo $e;
	}
}
