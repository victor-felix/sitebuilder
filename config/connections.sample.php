<?php

use lithium\data\Connections;

$mysql = array(
    'production' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'ipanemax2010',
        'database' => 'partners.meumobi',
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
