<?php

require dirname(__DIR__) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'app/models/users.php';
ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
ini_set('display_errors', 'On');
$_ = array_shift($argv);
$firstname = array_shift($argv);
$lastname = array_shift($argv);
$email = array_shift($argv);
$password = array_shift($argv);
$siteTitle = array_shift($argv);
$segment = array_shift($argv);

require 'segments/' . $segment . '/config.php';


$user = new Users();
$user->cantCreateSite = true;
$user->updateAttributes(array('firstname' => $firstname, 'lastname' => $lastname, 'email' => $email,
	'password' => $password, 'confirm_password' => $password, 'active' => 1));
$user->save();

Model::load('Sites');
$data = array(
	'title' => $siteTitle,
	'slug' => strtolower($siteTitle),
	'segment' => $segment,
);
$site = new Sites($data);
$site->save();
$relation = new UsersSites();
$relation->user_id = $user->id;
$relation->site_id = $site->id;
$relation->segment = $site->segment;
$relation->role = 1;
$relation->save();