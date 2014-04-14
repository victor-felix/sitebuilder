<?php

use lithium\data\Connections;

$mysql = array(
    'rimobi' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'meumobi',
        'password' => 'q1T10Cr',
        'database' => 'rimobi',
        'prefix' => ''
			),
		'production' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'ipanemax2010',
        'database' => 'meumobi_partners',
        'prefix' => ''
    ),
    'integration' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'meumobi',
        'password' => 'q1T10Cr',
        'database' => 'int_partners',
        'prefix' => ''
    )
);

$mongodb = array(
    'rimobi' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'rimobi'
			),
		'production' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'meumobi_partners'
    ),
    'integration' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'int_partners'
    )
);

$env = Config::read('App.environment');
Connection::add($mysql);
Connection::add('default', $mysql[$env]);
Connections::add('default', $mongodb[$env]);
