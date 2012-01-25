<?php

require dirname(dirname(__DIR__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';

$environment = Config::read('App.environment');
$connection = Connection::get($environment);
$connection->query('SET NAMES latin1');

$items = $connection->read(array('table' => 'business_items'));

while($item = $items->fetch()) {
    $attr = array(
        'site_id' => $item['site_id'],
        'parent_id' => $item['parent_id'],
        'type' => $item['type'],
        'order' => $item['order'],
        'created' => $item['created'],
        'modified' => $item['modified']
    );

    $item_values = $connection->read(array(
        'table' => 'business_items_values',
        'conditions' => array(
            'item_id' => $item['id']
        )
    ));

    while($item_value = $item_values->fetch()) {
        $attr[$item_value['field']] = $item_value['value'];
    };

    $classname = '\app\models\items\\' . Inflector::camelize($item['type']);
    $mongo_item = $classname::create($attr);
    $mongo_item->save();

    $connection->update(array(
        'table' => 'images',
        'conditions' => array(
            'model' => 'BusinessItems',
            'foreign_key' => $item['id']
        ),
        'values' =>  array(
            'model' => 'Items',
            'foreign_key' => $mongo_item->id()
        )
    ));
}

$connection->query('UPDATE images SET path = REPLACE(path, "images/business_items", "uploads/items");');
$connection->query('UPDATE images SET path = REPLACE(path, "images/", "uploads/");');
