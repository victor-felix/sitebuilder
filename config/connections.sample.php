<?php

use lithium\data\Connections;

$mysql = array(
    'development' => array(
        'driver' => 'MySql',
        'host' => '',
        'user' => '',
        'password' => '',
        'database' => '',
        'prefix' => ''
    ),
    'test' => array(
        'driver' => 'MySql',
        'host' => '',
        'user' => '',
        'password' => '',
        'database' => '',
        'prefix' => ''
    ),
    'production' => array(
        'driver' => 'MySql',
        'host' => '',
        'user' => '',
        'password' => '',
        'database' => '',
        'prefix' => ''
    ),
    'staging' => array(
        'driver' => 'MySql',
        'host' => '',
        'user' => '',
        'password' => '',
        'database' => '',
        'prefix' => ''
    )
);

$mongodb = array(
    'development' => array(
        'type' => 'MongoDb',
        'host' => '',
        'database' => ''
    ),
    'test' => array(
        'type' => 'MongoDb',
        'host' => '',
        'database' => ''
    ),
    'production' => array(
        'type' => 'MongoDb',
        'host' => '',
        'database' => ''
    ),
    'staging' => array(
        'type' => 'MongoDb',
        'host' => '',
        'database' => ''
    )
);

$env = Config::read('App.environment');
Connection::add($mysql);
Connection::add('default', $mysql[$env]);
Connections::add('default', $mongodb[$env]);
