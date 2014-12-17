<?php
use meumobi\sitebuilder\Item;
use app\models\Items;

require dirname(__DIR__) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
set_time_limit(0);
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', 'On');

function checkItems() {
	$data = [];
	$data['total_itens_images'] = 0;
	foreach (Items::find('all') as $item) {
		if (!Model::load('Categories')->count(['conditions' => ['id' => $item->parent_id]]))
			$data[$item->type] += 1;
			$data['total_itens_images'] += Model::load('Images')->count(['conditions' => ['foreign_key' => $item->_id]]);
	}
	return $data;
	//Model::load('Categories')
}

print_r(checkItems());