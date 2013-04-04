<?php

require dirname(__DIR__) . '/config/bootstrap.php';

use app\models\extensions\Rss;

set_time_limit(60 * 20);

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

$pidpath = APP_ROOT . '/tmp/update_priority_feeds.pid';
$pidfile = fopen($pidpath, 'w+');

if (!flock($pidfile, LOCK_EX | LOCK_NB)) exit();

fwrite($pidfile, getmypid());
fflush($pidfile);

echo date('Y-m-d H:i:s') . ': Updating priority feeds...' . PHP_EOL;

$stats = array(
	'total_feeds' => 0,
	'total_articles' => 0,
	'removed_articles' => 0,
	'total_images' => 0,
	'failed_images' => 0,
	'start_time' => microtime(true)
);

$extensions = Rss::find('all', array(
	'conditions' => array(
		'extension' => 'rss',
		'enabled' => 1,
		'priority' => array('$gte' => 1)
	)
));

foreach ($extensions as $extension) {
	try {
		$feed_stats = $extension->updateArticles();
		$stats['total_articles'] += $feed_stats['total_articles'];
		$stats['removed_articles'] += $feed_stats['removed_articles'];
		$stats['total_images'] += $feed_stats['total_images'];
		$stats['failed_images'] += $feed_stats['failed_images'];
		$stats['total_feeds'] += 1;
	} catch (Exception $e) {
		echo date('Y-m-d H:i:s') . ': Feed update error: ' . $e->getMessage() . PHP_EOL;
	}
}

$stats['end_time'] = microtime(true);

echo date('Y-m-d H:i:s') . ': Finished updating priority feeds.' . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Updated feeds: ' . $stats['total_feeds'] . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Imported articles: ' . $stats['total_articles'] . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Downloaded images: ' . $stats['total_images'] . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Removed articles: ' . $stats['removed_articles'] . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Failed image downloads: ' . $stats['failed_images'] . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Time (s): ' . ($stats['end_time'] - $stats['start_time']) . PHP_EOL;

fclose($pidfile);
unlink($pidpath);
