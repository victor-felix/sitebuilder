<?php

require dirname(__DIR__) . '/config/cli.php';

function get_migration_name($migration)
{
    return Inflector::camelize(substr(Filesystem::filename($migration), 15));
}

function migrate($migration, $connection)
{
    echo 'importing ' . $migration . '... ';

    $ext = Filesystem::extension($migration);

    if ($ext == 'php') {
        require_once 'db/migrations/' . $migration;
        $classname = get_migration_name($migration);
        $classname::migrate($connection);
    } else {
        $connection->query(Filesystem::read('db/migrations/' . $migration));
    }

    echo 'done' . PHP_EOL;
}

$environment = Config::read('App.environment');
$connection = Connection::get($environment);
$migration = $argv[1];

migrate($migration, $connection);
