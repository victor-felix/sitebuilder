<?php

require dirname(dirname(__DIR__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'app/models/users.php';

$_ = array_shift($argv);
$name = array_shift($argv);
$email = array_shift($argv);
$password = array_shift($argv);

$user = new Users();
$user->cantCreateSite = true;
$user->updateAttributes(array('name' => $name, 'email' => $email,
	'password' => $password, 'confirm_password' => $password, 'active' => 1));
$user->save();
