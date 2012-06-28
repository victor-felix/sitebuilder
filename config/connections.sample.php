<?php

use lithium\data\Connections;

$mysql = array(
    'production' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'meumobi',
        'password' => 'q1T10Cr',
        'database' => 'partners_meumobi',
        'prefix' => ''
    )
);

$mongodb = array(
    'production' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'partners_meumobi'
    )
);

$env = Config::read('App.environment');
Connection::add($mysql);
Connection::add('default', $mysql[$env]);
Connections::add('default', $mongodb[$env]);
