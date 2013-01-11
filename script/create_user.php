<?php

require dirname(__DIR__) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'app/models/users.php';

$_ = array_shift($argv);
$firstname = array_shift($argv);
$lastname = array_shift($argv);
$email = array_shift($argv);
$password = array_shift($argv);

$user = new Users();
$user->cantCreateSite = true;
$user->updateAttributes(array('firstname' => $firstname, 'lastname' => $lastname, 'email' => $email,
	'password' => $password, 'confirm_password' => $password, 'active' => 1));
$user->save();
