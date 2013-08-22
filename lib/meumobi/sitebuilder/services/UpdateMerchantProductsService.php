<?php

namespace meumobi\sitebuilder\services;

use app\models\extensions\GoogleMerchantFeed;
use app\models\items\MerchantProducts;
use lithium\data\Connections;

use Exception;
use Model;

class UpdateMerchantProductsService
{
	const PRIORITY_LOW = 0;
	const PRIORITY_HIGH = 1;

	protected $options;
	protected $logger;

	public function __construct(array $options = [])
	{
		$this->options = $options;
	}

	public function call()
	{
		$this->logger()->info('updating merchant products', [
			'priority' => $this->options['priority']
		]);

		$stats = ['start_time' => microtime(true)];

		$db = Connections::get('default')->connection;

		$result = $db->extensions->aggregate([
			['$match' => [
				'extension' => 'google-merchant-feed',
				'enabled' => 1,
				'priority' => $this->priorityCriteria()
			]],
			['$group' => [
				'_id' => '$url',
				'categories' => ['$addToSet' => [
					'product_type' => '$product_type',
					'category_id' => '$category_id'
				]],
			]]
		]);

		if (!$result['ok']) return;
		$feeds = $result['result'];

		foreach ($feeds as $feed) {
			try {
				$this->logger()->debug('downloading feed', ['url' => $feed['_id']]);
				$xml = new \SimpleXMLElement(file_get_contents($feed['_id']));
				$this->logger()->debug('finished downloading feed');

				$xml->registerXPathNamespace('g', 'http://base.google.com/ns/1.0');
				$products = $xml->xpath('channel/item');

				$categories = array_unique(array_map(function($category) {
					return $category['category_id'];
				}, $feed['categories']));

				foreach ($categories as $category) {
					Model::load('Categories')->firstById($category)->removeItems();
					$this->logger()->debug('cleaned category',
						['category' => $category]);
				}

				$categories = array_reduce($feed['categories'], function($categories, $category) {
					$product_types = explode('|',
						mb_convert_case($category['product_type'],
							MB_CASE_LOWER, 'UTF-8'));

					foreach ($product_types as $product_type) {
						$categories[$product_type] []= $category['category_id'];
					}

					return $categories;
				}, []);

				foreach ($products as $product) {
					$type = mb_convert_case(get($product, 'g:product_type'),
						MB_CASE_LOWER, 'UTF-8');

					if (!isset($categories[$type])) continue;

					$attr = [
						'title' => get($product, 'title'),
						'brand' => get($product, 'g:brand'),
						'description' => get($product, 'description'),
						'price' => get($product, 'g:price'),
						'availability' => get($product, 'g:availability'),
						'link' => get($product, 'link'),
						'product_id' => get($product, 'g:id'),
						'product_type' => $type
					];

					foreach ($categories[$type] as $category_id) {
						$attr['parent_id'] = $category_id;
						$attr['site_id'] = Model::load('Categories')->firstById($category_id)->site_id;
						$attr['type'] = 'merchant_products';
						$obj = MerchantProducts::create($attr);
						$obj->save();
						$this->logger()->debug('saved product',
							['title' => $attr['title']]);

						$result = Model::load('Images')->download($obj, get($product, 'g:image_link'), [
							'url' => get($product, 'g:image_link'),
							'visible' => 1
						]);

						$this->logger()->debug('downloaded image',
							['url' => get($product, 'g:image_link')]);
					}
				}

				$db->extensions->update([
					'extension' => 'google-merchant-feed',
					'enabled' => 1,
					'url' => $feed['_id']
				], ['$unset' => ['priority' => '']]);

				$this->logger()->debug('finished feed', ['url' => $feed['_id']]);
			} catch (Exception $e) {
				$this->logger->error('product update error', [
					'exception' => get_class($e),
					'message' => $e->getMessage(),
					'trace' => $e->getTraceAsString()]);
			}

		}

		$stats['end_time'] = microtime(true);
		$stats['elapsed_time'] = array_unset($stats, 'end_time') -
			array_unset($stats, 'start_time');

		$this->logger->info('finished updating products', $stats);
	}

	protected function priorityCriteria()
	{
		$priorities = [
			self::PRIORITY_HIGH => ['$gte' => 1],
			self::PRIORITY_LOW => ['$exists' => false],
		];

		return $priorities[$this->options['priority']];
	}
	
	protected function logger()
	{
		if ($this->logger) return $this->logger;

		if (isset($this->options['logger'])) {
			return $this->logger = $this->options['logger'];
		}

		$handler = new \Monolog\Handler\RotatingFileHandler($this->loggerPath());
		$logger = new \Monolog\Logger('sitebuilder.merchant_products',
			[$handler]);

		return $this->logger = $logger;
	}

	protected function loggerPath()
	{
		return APP_ROOT . '/' . $this->options['logger_path'];
	}
}

