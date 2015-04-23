<?php

require dirname(__DIR__) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';

function create_db($connection) {
 $connection->query(Filesystem::read('db/schema.sql');	
}

$environment = Config::read('App.environment');
$connection = Connection::get($environment);
create_db($connection);
