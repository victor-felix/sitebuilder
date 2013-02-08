<?php

require dirname(__DIR__) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';

set_time_limit(60 * 20);

ini_set('error_reporting', 1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

echo date('Y-m-d H:i:s') . ': Updating feeds...' . PHP_EOL;

$categories = Model::load('Categories')->all(array(
	'conditions' => array(
		'feed_url IS NOT NULL AND feed_url != ""'
	)
));

foreach($categories as $category) {
	try {
		$category->updateArticles();
	} catch(Exception $e) {}
}

$categories = Model::load('Categories')->all(array(
	'conditions' => array(
		'visibility = -1 AND (feed_url = "" OR feed_url IS NULL)'
	)
));

foreach($categories as $category) {
	try {
		$category->removeItems();
		$category->save();
	} catch(Exception $e) {}
}

echo date('Y-m-d H:i:s') . ': Finished updating feeds.' . PHP_EOL;
