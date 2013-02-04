<?php

require dirname(__DIR__) . '/config/bootstrap.php';

use app\models\extensions\Rss;

set_time_limit(60 * 20);

ini_set('error_reporting', 1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

$pidfile = APP_ROOT . '/tmp/update_feeds.pid';

if (file_exists($pidfile)) exit();

file_put_contents($pidfile, getmypid());

echo date('Y-m-d H:i:s') . ': Updating feeds...' . PHP_EOL;

$extensions = Rss::find('all', array(
	'conditions' => array(
		'extension' => 'rss',
		'enabled' => 1
	)
));

foreach ($extensions as $extension) {
	try {
		$extension->updateArticles();
	} catch (Exception $e) {}
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

unlink($pidfile);
