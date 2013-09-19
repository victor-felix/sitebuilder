<?php
use app\models\Extensions;
use app\models\extensions\Rss;
use Exception;

require dirname(__DIR__) . '/config/cli.php';

$extension = Rss::find('first', [
	'conditions' => [
	'extension' => 'rss',
	'enabled' => 1,
	'priority' => ['$exists' => false],
	]
]);

$extension->updateArticles();

$category = Extensions::category($extension);

$category->removeItems();