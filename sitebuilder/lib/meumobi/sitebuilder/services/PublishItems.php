<?php

namespace meumobi\sitebuilder\services;

use app\models\Items;
use meumobi\sitebuilder\WorkerManager;

class PublishItems extends Service
{
	const LOG_CHANNEL  = 'sitebuilder.publis_items';

	public function call()
	{
		$time = time();
		$items = Items::find('all', [
			'conditions' => [
				'is_published' => ['$ne' => true],
				'published' => ['$lt' => $time],
			]
		]);

		$this->logger()->info('items=' . count($items));

		foreach ($items as $item) {
			$this->logger()->info('item=' . $item->id() . ' is_published=true');
			$item->is_published = true;
			$item->save();

			if ($item->parent()->notification) {
				$this->logger()->info('item=' . $item->id() . ' push_notif=true');
				WorkerManager::enqueue('push_notification',  ['item_id' => $item->id()]);
			} else {
				$this->logger()->info('item=' . $item->id() . ' push_notif=false');
			}
		}
	}
}
