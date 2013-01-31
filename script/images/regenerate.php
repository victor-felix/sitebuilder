<?php

use meumobi\sitebuilder\Item;
use app\models\Items;

//set time limit to 20min
set_time_limit(60 * 20);

require dirname(dirname(__DIR__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'app/models/sites.php';

ini_set('error_reporting', 1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

//get log instance
$log = \KLogger::instance(\Filesystem::path('log'));
$_ = array_shift($argv);
$sitesIds = $argv;

/**
 * regenerate images for some object(Sites, Items)
 */
function regenerate($model, $images) {
	global $log;
	if (!$images) {
		$log->logInfo("has no image");
		return;
	}

	if (!is_array($images)) {
		$images = array($images);
	}
	$count = 0;
	foreach ($images as $image) {
		try {
			$image->regenerate($model);
		} catch (Exception $e){
			$log->logError($e->getMessage());
		}
		$count++;
	}
	$log->logInfo("total regenerated : $count");
	return $count;
}

if (!$sitesIds) {
	throw new Exception('Site param is invalid!');
}

//get sites
if (reset($sitesIds) == '*') {
	$sites = Model::load('Sites')->all(array());
} else {
	$sites = Model::load('Sites')->allById($sitesIds);
}

//verify if has some site
if (!count($sites)) {
	throw new Exception('Site(s) do not exist!');
}

foreach ($sites as $site) {
	$log->logInfo("regenerating Site Photos to the site: {$site->id}");
	regenerate(new SitePhotos($site->id), $site->photos());

	$log->logInfo("regenerating Site Logos to the site: {$site->id}");
	regenerate(new SiteLogos($site->id), $site->logo());

	$itemsIds = Items::find('list', array('conditions' => array(
		'site_id' => $site->id
	)));

	foreach (array_keys($itemsIds) as $itemId) {
		$item = Items::create(array('_id' => $itemId));
		$log->logInfo("regenerating Images to the item: $itemId in the site: {$site->id}");
		regenerate($item, $item->images());
	}
}
