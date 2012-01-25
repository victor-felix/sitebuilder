<?php

require dirname(dirname(__DIR__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'lib/csv/CSVHandler.php';

$table = $argv[1];
$file = $argv[2];

$data = new CSVHandler($file, ',');
$data = $data->ReadCSV();

foreach($data as $row) {
    if($table == 'items') {
        $classname = '\app\models\items\\' . Inflector::camelize($row['type']);
        $record = $classname::create($row);
        $record->save();
    }
    else {
        $classname = Inflector::camelize($table);
        Model::load($classname);
        $record = new $classname($row);
        $record->save();
    }
}
