<?php

namespace meumobi\sitebuilder\services;

use app\models\extensions\EventFeed;
use app\models\items\Events;
use Exception;
use lithium\data\Connections;
use meumobi\sitebuilder\Logger;
use Model;
use SimpleXMLElement;

class UpdateEventsService extends Service {
	public function call() {
		Logger::info('events', 'updating events from feeds', [
			'priority' => $this->options['priority']
		]);
		$stats = ['start_time' => microtime(true)];
		$db = Connections::get('default')->connection;
		$extensions = $db->extensions->find ( [
				'extension' => 'event-feed',
				'enabled' => 1,
				'priority' => $this->priorityCriteria ()
			]);

		foreach ($extensions as $extension) {
			try {
				Logger::info('events', 'downloading feed', ['url' => $extension['url']]);
				$events = new SimpleXMLElement(file_get_contents($extension['url']));
				Logger::info('events', 'finished downloading feed');
				$category = Model::load('Categories')->firstById($extension['category_id']);

				foreach ($events as $event) {
					$hasItem = Events::find('count', array('conditions' => array(
						'parent_id' => $category->id,
						'guid' => (string)$event['id']
					)));

					if (!$hasItem) {
						$data = array (
								'site_id' => $category->site_id,
								'parent_id' => $category->id,
								'guid' => (string)$event['id'],
								'title' => strip_tags($event->title),
								'description' => (string)$event->description,
								'address' => (string)$event->where,
								'start_date' => (string)$event->start_date,
								'end_date' => (string)$event->end_date,
								'type' => 'events',
						);

						Events::create($data)->save();
						Logger::info('events', 'saved event', ['title' => $data['title']]);
					}
				}

				$db->extensions->update([
					'extension' => 'event-feed',
					'_id' => $extension['_id']
				], ['$unset' => ['priority' => '']]);

				Logger::info('events', 'finished feed', ['url' => $extension['url']]);
			} catch (Exception $e) {
				EventFeed::update(['enabled' => false], ['_id' => $extension['_id']]);

				Logger::info('events', 'feed update error, extension has disabled', [
					'error' => $e->getMessage(),
					'extension id' => $extension['_id'],
					'category id' => $extension['category_id'],
				]);
			}
		}

		$stats['end_time'] = microtime(true);
		$stats['elapsed_time'] = array_unset($stats, 'end_time') -
		array_unset($stats, 'start_time');
		Logger::info('events', 'finished updating events from feeds', $stats);
	}
}
