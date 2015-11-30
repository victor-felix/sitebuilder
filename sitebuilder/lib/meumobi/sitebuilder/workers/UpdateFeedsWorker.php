<?php

namespace meumobi\sitebuilder\workers;

use Exception;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\roles\Updatable;
use meumobi\sitebuilder\services\UpdateNewsFeed;
use meumobi\sitebuilder\validators\ParamsValidator;

class UpdateFeedsWorker
{
	use Updatable;

	const COMPONENT = 'update_news_feed';

	public function perform($params)
	{
		list($priority) = ParamsValidator::validate($params, ['priority']);

		$extensions = $this->getExtensionsByPriorityAndType($priority, 'rss');

		foreach($extensions as $extension) {
			try {
				$category = $this->getCategory($extension);
				$updateNewsFeed = new UpdateNewsFeed();

				$updateNewsFeed->perform(compact('category', 'extension'));
			} catch (Exception $e) {
				Logger::error(self::COMPONENT, 'caught exception', [
					'extension_id' => $extension->id(),
					'category_id' => $extension->category_id,
					'site_id' => $extension->site_id,
					'message' => $e->getMessage(),
					'exception'  => $e,
				]);
			}
		}
	}
}
