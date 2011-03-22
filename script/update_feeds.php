<?php

require dirname(dirname(__FILE__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';

$feeds = Model::load('Feeds')->all();

foreach($feeds as $feed) {
    $feed->updateArticles();
}