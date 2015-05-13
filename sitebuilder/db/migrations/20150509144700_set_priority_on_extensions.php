<?php

use lithium\data\Connections;
use app\models\Extensions;
use meumobi\sitebuilder\workers\Worker;

class SetPriorityOnExtensions
{
	public static function migrate($connection)
	{
		$events = Extensions::update(['priority' => Worker::PRIORITY_LOW], [
			'extension' => 'rss',
			'priority' =>['!=' => Worker::PRIORITY_HIGH]
		]);
	}
}
