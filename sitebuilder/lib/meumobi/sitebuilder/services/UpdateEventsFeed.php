<?php

namespace meumobi\sitebuilder\services;

use SimpleXMLElement;
use app\models\Extensions;
use app\models\items\Events;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class UpdateEventsFeed
{
	public function perform($params)
	{
		list($category, $extension) = ParamsValidator::validate($params,
			['category', 'extension']);

		$feed = $this->fetchFeed($extension->url);
		$events = $this->extractevents($feed, $category);

		$shouldCreate = function($event) {
			return !Events::find('count', [
				'conditions' => [
					'parent_id' => $event->parent_id,
					'guid' => $event->guid,
				],
			]);
		};

		$bulkImport = new BulkImportItems();
		$stats = $bulkImport->perform([
			'category' => $category,
			'items' => $events,
			'mode' => $extension->import_mode,
			'shouldCreate' =>	$shouldCreate,
		]);

		$category->updated = date('Y-m-d H:i:s');
		$category->save();

		$extension->priority = Extensions::PRIORITY_LOW;
		$extension->save(null, ['callbacks' => false]);

		Logger::info('extensions', 'extension priority lowered', [
			'extension_id' => (string) $extension->_id,
			'category_id' => $extension->category_id,
		]);

		return $stats;
	}

	protected function fetchFeed($url)
	{
		Logger::info('events', 'downloading feed', ['url' => $url]);
		return new SimpleXMLElement(file_get_contents($url));
	}

	protected function extractEvents($feed, $category)
	{
		$events = [];
		//tried with array_map, but din't worked because on the SimpleXmlElement
		foreach ($feed as $item) {
			$event = Events::create();
			$event->set([
				'site_id' => $category->site_id,
				'parent_id' => $category->id,
				'guid' => (string)$item['id'],
				'title' => strip_tags($item->title),
				'description' => (string)$item->description,
				'address' => (string)$item->where,
				'start_date' => (string)$item->start_date,
				'end_date' => (string)$item->end_date,
				'type' => 'events',
			]);
			$events[] = $event;
		}

		return $events;
	}
}
