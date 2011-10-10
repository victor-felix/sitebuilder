<?php

use lithium\data\Connections;

$mysql = array(
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
);

$mongodb = array(
    'development' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'meu_site_builder'
    ),
    'test' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'meu_site_builder'
    ),
    'production' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'meu_site_builder'
    ),
    'staging' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'meu_site_builder'
    )
);

$env = Config::read('App.environment');
Connection::add($mysql);
Connection::add('default', $mysql[$env]);
Connections::add('default', $mongodb[$env]);
