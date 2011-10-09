<?php

Connection::add(array(
		'development' => array(
        'driver' => 'MySql',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => '',
        'database' => 'meu-site-builder',
        'prefix' => ''
    ),
    'test' => array(
        'driver' => 'MySql',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => '',
        'database' => 'meu-site-builder-test',
        'prefix' => ''
    ),
    'production' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'imax',
        'password' => 'q1T10Cr',
        'database' => 'meu-site-builder',
        'prefix' => ''
    ),
    'staging' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'imax',
        'password' => 'q1T10Cr',
        'database' => 'meu-site-builder',
        'prefix' => ''
    )
));

$env = Config::read('App.environment');
Connection::add('default', Connection::config($env));
