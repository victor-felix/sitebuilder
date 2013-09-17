<?php

namespace meumobi\sitebuilder\services;

use lithium\data\Connections;

use app\models\extensions\Rss;
use Exception;

class UpdateFeedsService
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
		$this->logger()->info('updating feeds', [
			'priority' => $this->options['priority']
		]);

		$stats = [
			'total_feeds' => 0,
			'total_articles' => 0,
			'removed_articles' => 0,
			'total_images' => 0,
			'failed_images' => 0,
			'start_time' => microtime(true)
		];

		$connection = Connections::get('default')->connection;

		$extensionsCursor = $connection->extensions->find([
			'extension' => 'rss',
			'enabled' => 1,
			'priority' => $this->priorityCriteria()
		]);
		//set the initial timeout to 30 seconds
		$extensionsCursor->timeout(30000);
		foreach ($extensionsCursor as $extensionData) {
			//wait 3 minutes on each interation
			$extensionsCursor->timeout(180000);
			try {
				$data = $extensionData;
				$data['_id'] = (string) $data['_id'];

				$extension = Rss::create($data);
				$feed_stats = $extension->updateArticles();
				$stats['total_articles'] += $feed_stats['total_articles'];
				$stats['removed_articles'] += $feed_stats['removed_articles'];
				$stats['total_images'] += $feed_stats['total_images'];
				$stats['failed_images'] += $feed_stats['failed_images'];
				$stats['total_feeds'] += 1;
			} catch (Exception $e) {
				$this->logger->error('rss update error', [
					'exception' => get_class($e),
					'message' => $e->getMessage(),
					'trace' => $e->getTraceAsString()]);
			}
		}

		$stats['end_time'] = microtime(true);
		$stats['elapsed_time'] = array_unset($stats, 'end_time') -
			array_unset($stats, 'start_time');

		$this->logger->info('finished updating feeds', $stats);
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
		$logger = new \Monolog\Logger('sitebuilder.feeds', [$handler]);

		return $this->logger = $logger;
	}

	protected function loggerPath()
	{
		return APP_ROOT . '/' . $this->options['logger_path'];
	}
}
