<?php

require dirname(dirname(__FILE__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';

$categories = Model::load('Categories')->all(array(
    'conditions' => array(
        'feed_url IS NOT NULL AND feed_url != ""'
    )
));

foreach($categories as $category) {
    $category->updateArticles();
}
