<?php

use lithium\data\Connections;
use app\models\items\Events;

class AddStartDateToEvents
{
	public static function migrate($connection)
	{
		$events = Events::find('all', array('conditions' => array(
				'type' => 'events',
				'date' => ['$exists' => true]
		)));
		foreach ($events as $event) {
			$plusOneHour = $event->date->sec + 60*60;
			$event->start_date = $event->date;
			$event->end_date = $plusOneHour;
			$event->save();
		}
	}
}
