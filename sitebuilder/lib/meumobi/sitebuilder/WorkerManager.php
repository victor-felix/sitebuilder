<?php

namespace meumobi\sitebuilder;

class WokerManager
{
	public static function enqueue($type, $params)
	{
		$job = \app\models\Jobs::create([
			'type' => $type,
			'params' => $params
		]);
		return $job->save();
	}

	public static function execute($worker)
	{
		try {
			$worker::perform();
			self::destroy($worker);
		} catch (Exception $e) {
			//job failed
		}
	}

	public static function getNextJobWorker()
	{
		
	}
}

