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
        $type = isset($row['type']) ? $row['type'] : getenv('MOBI_TYPE');
        $classname = '\app\models\items\\' . Inflector::camelize($type);
        $record = $classname::create($row);
        $record->type = $type;
        $record->parent_id = $record->parent_id ?: getenv('MOBI_CATEGORY');
        $record->site_id = Model::load('Categories')->firstById($record->parent_id)->site_id;
        $record->save();
    }
    else {
        $classname = Inflector::camelize($table);
        Model::load($classname);
        $record = new $classname($row);
        $record->save();
    }
}
