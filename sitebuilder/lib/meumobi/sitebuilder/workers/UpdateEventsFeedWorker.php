<?php

namespace meumobi\sitebuilder\workers;

use Exception;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\roles\Updatable;
use meumobi\sitebuilder\services\UpdateEventsFeed;
use meumobi\sitebuilder\validators\ParamsValidator;

class UpdateEventsFeedWorker
{
	use Updatable;

	public function perform($params)
	{
		list($priority) = ParamsValidator::validate($params, ['priority']);

		Logger::info('workers', 'updating events feeds', [
			'priority' => $priority,
		]);

		$start = microtime(true);
		$stats = [
			'total_updated_feeds' => 0,
			'total_failed_feeds' => 0,
			'failed_feeds'=> [],
			'categories' => [],
			'priority' => $priority,
		];

		$extensions = $this->getExtensionsByPriorityAndType($priority, 'event-feed');

		foreach($extensions as $extension) {
			try {
				$category = $this->getCategory($extension);
				$updateEventsFeed = new UpdateEventsFeed();

				$stats['categories'][$category->id] =
					$updateEventsFeed->perform(compact('category', 'extension'));
				$stats['total_updated_feeds'] += 1;
			} catch (Exception $e) {
				$stats['total_failed_feeds'] += 1;
				$stats['failed_feeds'][] = [
					'extension_id' => (string) $extension->_id,
					'category_id' => $extension->category_id,
					'site_id' => $extension->site_id,
					'error' => $e->getMessage(),
				];
			}
		}

		$stats['elapsed_time'] = microtime(true) - $start;
		Logger::info('workers', 'finished updating events feeds', $stats);
	}
}
