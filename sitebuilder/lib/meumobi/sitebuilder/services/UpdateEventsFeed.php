<?php

namespace meumobi\sitebuilder\services;

use SimpleXMLElement;
use app\models\Extensions;
use app\models\items\Events;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class UpdateEventsFeed
{
	const COMPONENT = 'update_events_feed';

	public function perform($params)
	{
		list($category, $extension) = ParamsValidator::validate($params, [
			'category',
			'extension',
		]);

		$feed = $this->fetchFeed($extension->url, $extension);
		$events = $this->extractevents($feed, $category);

		$bulkImport = new BulkImportItems();
		$stats = $bulkImport->perform([
			'category' => $category,
			'items' => $events,
			'mode' => $extension->import_mode,
		]);

		$category->updated = date('Y-m-d H:i:s');
		$category->save();

		if ($extension->priority != Extensions::PRIORITY_LOW) {
			$extension->priority = Extensions::PRIORITY_LOW;
			$extension->save(null, ['callbacks' => false]);

			Logger::info(self::COMPONENT, 'extension priority lowered', [
				'extension_id' => $extension->id(),
				'category_id' => $extension->category_id,
			]);
		}

		return $stats;
	}

	protected function fetchFeed($url, $extension)
	{
		Logger::info(self::COMPONENT, 'fetching feed', [
			'url' => $url,
			'extension_id' => $extension->id(),
			'category_id' => $extension->category_id,
		]);

		$feed = file_get_contents($url);

		Logger::info(self::COMPONENT, 'feed fetched', [
			'url' => $url,
			'extension_id' => $extension->id(),
			'category_id' => $extension->category_id,
		]);

		return new SimpleXMLElement($feed);
	}

	protected function extractEvents($feed, $category)
	{
		$events = [];

		foreach ($feed as $item) {
			$event = Events::find('first', [
				'conditions' => [
					'parent_id' => $category->id,
					'guid' => (string) $item['id'],
				],
			]);

			$event = $event ?: Events::create();

			$event->set([
				'site_id' => $category->site_id,
				'parent_id' => $category->id,
				'guid' => (string) $item['id'],
				'title' => strip_tags($item->title),
				'description' => (string) $item->description,
				'address' => (string) $item->where,
				'start_date' => (string) $item->start_date,
				'end_date' => (string) $item->end_date,
				'type' => 'events',
			]);
			$events[] = $event;
		}

		return $events;
	}
}
