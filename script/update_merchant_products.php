<?php

require dirname(__DIR__) . '/config/bootstrap.php';
require dirname(__DIR__) . '/config/error_handler.php';

use app\models\extensions\GoogleMerchantFeed;
use app\models\items\MerchantProducts;

set_time_limit(60 * 20);

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');
Config::write('Debug.showErrors', true);

$pidpath = APP_ROOT . '/tmp/update_merchant_products.pid';
$pidfile = fopen($pidpath, 'w+');

if (!flock($pidfile, LOCK_EX | LOCK_NB)) exit();

fwrite($pidfile, getmypid());
fflush($pidfile);

function get($product, $key) {
	$value = $product->xpath($key);

	if (isset($value[0])) {
		return (string) $value[0];
	} else {
		return null;
	}
}

echo date('Y-m-d H:i:s') . ': Updating products...' . PHP_EOL;

$stats = array(
	'start_time' => microtime(true)
);

$db = lithium\data\Connections::get('default')->connection;

$result = $db->extensions->aggregate(array(
	array(
		'$match' => array(
			'extension' => 'google-merchant-feed',
			'enabled' => 1
		),
	),
	array(
		'$group' => array(
			'_id' => '$url',
			'categories' => array('$addToSet' => array(
				'product_type' => '$product_type',
				'category_id' => '$category_id'
			))
		)
	)
));

if ($result['ok']) {
	$feeds = $result['result'];

	foreach ($feeds as $feed) {
		try {
			echo date('Y-m-d H:i:s') . ': Downloading feed ' . $feed['_id'] . PHP_EOL;
			$xml = new \SimpleXMLElement(file_get_contents($feed['_id']));
			$xml->registerXPathNamespace('g', 'http://base.google.com/ns/1.0');
			$products = $xml->xpath('channel/item');
			echo date('Y-m-d H:i:s') . ': Finished downloading feed ' . PHP_EOL;

			$categories = array_unique(array_map(function($category) {
				return $category['category_id'];
			}, $feed['categories']));

			foreach ($categories as $category) {
				\Model::load('Categories')->firstById($category)->removeItems();
				echo date('Y-m-d H:i:s') . ': Cleaned category ' . $category . PHP_EOL;
			}

			$categories = array_reduce($feed['categories'], function($categories, $category) {
				$product_types = explode('|', mb_convert_case($category['product_type'], MB_CASE_LOWER, "UTF-8"));
				foreach ($product_types as $product_type) {
					$categories[$product_type] []= $category['category_id'];
				}
				return $categories;
			}, array());

			foreach ($products as $product) {
				$type = mb_convert_case(get($product, 'g:product_type'), MB_CASE_LOWER, "UTF-8");

				if (isset($categories[$type])) {
					$attr = array(
						'title' => get($product, 'title'),
						'brand' => get($product, 'g:brand'),
						'description' => get($product, 'description'),
						'price' => get($product, 'g:price'),
						'availability' => get($product, 'g:availability'),
						'link' => get($product, 'link'),
						'product_id' => get($product, 'g:id'),
						'product_type' => $type
					);

					foreach ($categories[$type] as $category_id) {
						$attr['parent_id'] = $category_id;
						$attr['site_id'] = Model::load('Categories')->firstById($category_id)->site_id;
						$attr['type'] = 'merchant_products';
						echo date('Y-m-d H:i:s') . ': Saving product ' . $attr['title'] . ' to category ' . $category_id . PHP_EOL;
						$obj = MerchantProducts::create($attr);
						$obj->save();

						echo date('Y-m-d H:i:s') . ': Downloading image ' . get($product, 'g:image_link') . PHP_EOL;
						$result = Model::load('Images')->download($obj, get($product, 'g:image_link'), array(
							'url' => get($product, 'g:image_link'),
							'visible' => 1
						));
					}
				}
			}
			echo date('Y-m-d H:i:s') . ': Finished feed ' . $feed['_id'] . PHP_EOL;
		} catch (Exception $e) {
			echo date('Y-m-d H:i:s') . ': Product update error: ' . $e->getMessage() . PHP_EOL;
			echo $e;
		}
	}
}

$stats['end_time'] = microtime(true);

echo date('Y-m-d H:i:s') . ': Finished updating products.' . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Time (s): ' . ($stats['end_time'] - $stats['start_time']) . PHP_EOL;

fclose($pidfile);
unlink($pidpath);
