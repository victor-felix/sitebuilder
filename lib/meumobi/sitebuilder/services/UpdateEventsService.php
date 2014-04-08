<?php

namespace meumobi\sitebuilder\services;

use app\models\extensions\EventFeed;
use app\models\items\Events;
use lithium\data\Connections;
use Exception;
use Model;

class UpdateEventsService extends Service {
	public function __construct(array $options = []) {
		$this->options = $options;
	}
	public function call() {
		$db = Connections::get('default')->connection;
		$feeds = $db->extensions->find ( [ 
				'extension' => 'event-feed',
				'enabled' => 1,
				'priority' => $this->priorityCriteria () 
		] );
		foreach ($feeds as $feed) {
			try {
				$events = new \SimpleXMLElement(file_get_contents($feed['url']));
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
					}
				}
				$db->extensions->update([
					'extension' => 'event-feed',
					'_id' => $feed['_id']
				], ['$unset' => ['priority' => '']]);
			} catch ( Exception $e ) {
				echo $e->getMessage();
			}
		}
	}
}

