<?php

Config::write('Sites.blacklist', array());

Config::write('Geocode.urls', array(
	'http://maps.googleapis.com',
	'http://elefante.ipanemax.com',
	'http://laguna.ipanemax.com',
	'http://branca.ipanemax.com',
	'http://bonita.ipanemax.com',
));

Config::write('SiteLogos.resizes', array('200x200'));
Config::write('SitePhotos.resizes', array('139x139#', '314x220'));
Config::write('BusinessItems.resizes', array('80x60#', '80x80#', '139x139#', '314x220'));

Config::write('Segments', array(
	'example' => array(
		'title' => 'MeuMobi Enterprise',
		'items' => array('articles', 'business'),
		'root' => 'Index',
		'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
		'hideCategories' => 0,
		'enableSignup' => 0,
	),
	'1440group' => array(
		'title' => '1440group',
		'items' => array('articles', 'business'),
		'root' => 'Index',
		'email' => array('no-reply@1440group.com' => '1440group'),
		'hideCategories' => 0,
		'enableSignUp' => 0,
	),
	'ipanemax' => array(
		'title' => 'Ipanemax',
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
Config::write('multiInstances', 1);
