<?php

Config::write('Sites.blacklist', array());

Config::write('SiteLogos.resizes', array('200x200'));
Config::write('SitePhotos.resizes', array('80x80#', '139x139#', '320x200#'));
Config::write('BusinessItems.resizes', array('80x60#', '85x85#', '80x80#', '30x30#', '139x139#', '173x154#'));

Config::write('Segments', array(
	'example' => array(
		'title' => 'Example Segment',
		'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores', 'users'),
		'root' => 'Index',
		'email' => array('no-reply@example.com' => 'Example'),
		'hideCategories' => 1
	),
	'1440group' => array(
		'title' => '1440group',
		'items' => array('articles', 'links', 'business'),
		'root' => 'Index',
		'email' => array('no-reply@1440group.com' => '1440group'),
		'hideCategories' => 0,
		'enableSignUp' => 1
	),
	'ipanemax' => array(
		'title' => 'Oi',
		'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores', 'users'),
		'root' => 'Index',
		'email' => array('no-reply@ipanemax.com' => 'ipanemax'),
		'hideCategories' => 0,
		'enableSignUp' => 0
	),
	'oi' => array(
		'title' => 'Oi',
		'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores', 'users'),
		'root' => 'Index',
		'email' => array('no-reply@oi.com' => 'Oi'),
		'hideCategories' => 0,
		'enableSignUp' => 0
	),
	'agencia3' => array(
		'title' => 'Agencia3',
		'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores', 'users'),
		'root' => 'Index',
		'email' => array('no-reply@agencia3.com' => 'Agencia3'),
		'hideCategories' => 0,
		'enableSignUp' => 0
	),
));

Config::write('Sites.domain', 'int-meumobi.com');
