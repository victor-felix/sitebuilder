<?php

namespace meumobi\sitebuilder;

use app\models\Jobs;

class WorkerManager
{
	public static function enqueue($type, $params)
	{
		$job = Jobs::create([
			'type' => $type,
			'params' => $params
		]);
		return $job->save();
	}

	public static function execute($worker)
	{
		$worker::perform();
		self::destroy($worker);
	}

	public static function getNextJobWorker()
	{
		$job = Jobs::first(['order' => 'modified']);
		if ($job) {
			$workerClass = 'meumobi\sitebuilder\workers\\' . \Inflector::camelize($job->type) . 'Worker';
			return new $workerClass(compact('job'));
		}
	}

	public static function destroy($worker)
	{
		Jobs::remove(['_id' => $worker->job()->_id]);
	}
}
