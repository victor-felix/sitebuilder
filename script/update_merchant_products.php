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
			$xml = new \SimpleXMLElement(file_get_contents($feed['_id']));
			$xml->registerXPathNamespace('g', 'http://base.google.com/ns/1.0');
			$products = $xml->xpath('channel/item');

			$categories = array_unique(array_map(function($category) {
				return $category['category_id'];
			}, $feed['categories']));

			foreach ($categories as $category) {
				\Model::load('Categories')->firstById($category)->removeItems();
			}

			$categories = array_reduce($feed['categories'], function($categories, $category) {
				$product_types = explode('|', mb_convert_case($category['product_type'], MB_CASE_LOWER, "UTF-8"));
				foreach ($product_types as $product_type) {
					$categories[$product_type] []= $category['category_id'];
				}
				return $categories;
			}, array());

			foreach ($products as $product) {
				$type = mb_convert_case((string) $product->xpath('g:product_type')[0], MB_CASE_LOWER, "UTF-8");

				if (isset($categories[$type])) {
					$attr = array(
						'title' => (string) $product->xpath('title')[0],
						'brand' => (string) $product->xpath('g:brand')[0],
						'description' => (string) $product->xpath('description')[0],
						'price' => (string) $product->xpath('g:price')[0],
						'availability' => (string) $product->xpath('g:availability')[0],
						'link' => (string) $product->xpath('link')[0],
						'product_id' => (string) $product->xpath('g:id')[0],
						'product_type' => $type
					);

					foreach ($categories[$type] as $category_id) {
						$attr['parent_id'] = $category_id;
						$attr['site_id'] = Model::load('Categories')->firstById($category_id)->site_id;
						$attr['type'] = 'merchant_products';
						$obj = MerchantProducts::create($attr);
						$obj->save();

						$result = Model::load('Images')->download($obj, (string) $product->xpath('g:image_link')[0], array(
							'url' => (string) $product->xpath('g:image_link')[0],
							'visible' => 1
						));
					}
				}
			}
		} catch (Exception $e) {
			echo date('Y-m-d H:i:s') . ': Product update error: ' . $e->getMessage() . PHP_EOL;
		}
	}
}

$stats['end_time'] = microtime(true);

echo date('Y-m-d H:i:s') . ': Finished updating products.' . PHP_EOL;
echo date('Y-m-d H:i:s') . ': Time (s): ' . ($stats['end_time'] - $stats['start_time']) . PHP_EOL;

fclose($pidfile);
unlink($pidpath);
