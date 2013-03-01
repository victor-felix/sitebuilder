<?php

require dirname(__DIR__) . '/config/bootstrap.php';

use app\models\extensions\Rss;

set_time_limit(60 * 20);

ini_set('error_reporting', 1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

$pidpath = APP_ROOT . '/tmp/update_feeds.pid';
$pidfile = fopen($pidpath, 'w+');

if (!flock($pidfile, LOCK_EX | LOCK_NB)) exit();

fwrite($pidfile, getmypid());

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
	} catch (Exception $e) {
		echo $e->getMessage() . PHP_EOL;
	}
}

echo date('Y-m-d H:i:s') . ': Finished updating feeds.' . PHP_EOL;

fclose($pidfile);
unlink($pidpath);
