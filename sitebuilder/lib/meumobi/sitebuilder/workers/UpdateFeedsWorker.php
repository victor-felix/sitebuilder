<?php

namespace meumobi\sitebuilder\workers;

use Exception;
use app\models\extensions\Rss;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\repositories\RecordNotFoundException;
use meumobi\sitebuilder\services\UpdateNewsFeed;
use meumobi\sitebuilder\validators\ParamsValidator;

class UpdateFeedsWorker
{
	public function perform($params)
	{
		list($priority) = ParamsValidator::validate($params, ['priority']);

		Logger::info('workers', 'updating feeds', [
			'priority' => $priority
		]);

		$start = microtime(true);
		$stats = [
			'total_updated_feeds' => 0,
			'total_failed_feeds' => 0,
			'failed_feeds'=> [],
			'categories' => [],
			'priority' => $priority
		];

		$extensions = $this->getExtensionsByPriority($priority);

		foreach($extensions as $extension) {
			try {
				$category = $this->getCategory($extension);
				$updateNewsFeed = new UpdateNewsFeed();
				$stats['categories'][$category->id] =
					$updateNewsFeed->perform(compact('category', 'extension'));
				$stats['total_updated_feeds'] += 1;
			} catch (Exception $e) {
				$stats['total_failed_feeds'] += 1;
				$stats['failed_feeds'][] = [
					'extension_id' => (string) $extension->id,
					'category_id' => $extension->category_id,
					'site_id' => $extension->site_id,
					'error' => $e->getMessage(),
				];
			}
		}

		$stats['elapsed_time'] = microtime(true) - $start;
		Logger::info('workers', 'finished updating feeds', $stats);
	}

	protected function getExtensionsByPriority($priority)
	{
		return Rss::find('all', [
			'conditions' => [
				'extension' => 'rss',
				'enabled' => 1,
				'priority' => $priority,
			],
		]);
	}

	protected function getCategory($extension)
	{
		try {
			return Rss::category($extension);
		} catch (RecordNotFoundException $e) {
			$extension->enabled = 0;
			$extension->save();
			throw new Exception('Invalid extension category');
		}
	}
}

