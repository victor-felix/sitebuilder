<?php
use meumobi\sitebuilder\Item;
use app\models\Extensions;
use app\models\Items;

require dirname(__DIR__) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
set_time_limit(0);
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', 'On');

function checkItems() {
	$total = 0;
	foreach (Extensions::find('all') as $item) {
		if (!Model::load('Categories')->count(array ('conditions' => ['id' => $item->category_id]))) {
			$itemData = $item->to('array');
			$itemData['modified'] = date(DATE_ATOM, $itemData['modified']);
			$string = print_r($itemData, true);
			echo str_replace(array("\r\n", "\r", "\n", '>',']','[', 'Array')
					, "", 
					$string), "\n";
			$total++;
		}
	}
	echo "total: $total\n";
	return $total;
}

checkItems();