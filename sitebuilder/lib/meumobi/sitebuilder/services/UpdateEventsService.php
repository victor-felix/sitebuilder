<?php

namespace meumobi\sitebuilder\services;

use app\models\extensions\EventFeed;
use app\models\items\Events;
use lithium\data\Connections;
use Model;

class UpdateEventsService extends Service {
	public function call() {
		$this->logger()->info('updating events', [
				'priority' => $this->options['priority']
				]);
		$stats = ['start_time' => microtime(true)];

		$db = Connections::get('default')->connection;
		$feeds = $db->extensions->find ( [
				'extension' => 'event-feed',
				'enabled' => 1,
				'priority' => $this->priorityCriteria ()
		] );
		foreach ($feeds as $feed) {
			try {
				$this->logger()->debug('downloading feed', ['url' => $feed['url']]);
				$events = new \SimpleXMLElement(file_get_contents($feed['url']));
				$this->logger()->debug('finished downloading feed');
				$category = Model::load('Categories')->firstById($feed['category_id']);
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
						//print_r($data); continue;
						Events::create($data)->save();
						$this->logger()->debug('saved event',
								['title' => $data['title']]);
					}
				}
				$db->extensions->update([
					'extension' => 'event-feed',
					'_id' => $feed['_id']
				], ['$unset' => ['priority' => '']]);
				$this->logger()->debug('finished feed', ['url' => $feed['url']]);
			} catch ( Exception $e ) {
				$this->logger->error('events update error', [
						'exception' => get_class($e),
						'message' => $e->getMessage(),
						'trace' => $e->getTraceAsString()]);
			}
			$stats['end_time'] = microtime(true);
			$stats['elapsed_time'] = array_unset($stats, 'end_time') -
			array_unset($stats, 'start_time');
			$this->logger->info('finished updating events', $stats);
		}
	}
}
