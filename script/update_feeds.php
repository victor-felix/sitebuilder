<?php

require dirname(__DIR__) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';

echo date('Y-m-d H:i:s') . ': Updating feeds...' . PHP_EOL;

$categories = Model::load('Categories')->all(array(
    'conditions' => array(
        'feed_url IS NOT NULL AND feed_url != ""'
    )
));

foreach($categories as $category) {
    $category->updateArticles();
}
