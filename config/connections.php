<?php

Connection::add(array(
    'development' => array(
        'driver' => 'MySql',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => '',
        'database' => 'mobuilder',
        'prefix' => ''
    ),
    'production' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'bkuser',
        'password' => 'bkuser',
        'database' => 'mobile-site-builder',
        'prefix' => ''
    )
));

$env = Config::read('App.environment');
Connection::add('default', Connection::config($env));
