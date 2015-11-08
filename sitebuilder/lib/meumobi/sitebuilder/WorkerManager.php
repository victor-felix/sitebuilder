<?php

namespace meumobi\sitebuilder;

use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\workers\Worker;
use app\models\Jobs;

class WorkerManager
{
	public static function enqueue($type, $params, $priority = Worker::PRIORITY_LOW)
	{
		$job = Jobs::create([
			'type' => $type,
			'priority' => $priority,
			'params' => $params
		]);
		$job->save();
		Logger::info('workers', 'job created', [
			'job id' => $job->id(),
			'type' => $type,
			'priority' => $priority,
			'params' => $params,
		]);
		return $job;
	}

	public static function isEnqueued($type, $params, $priority = Worker::PRIORITY_LOW)
	{
		$conditions = [
			'type' => $type,
			'priority' => $priority
		];
		foreach ($params as $param => $value) {
			$conditions["params.$param"] = $value;
		}
		return (bool)Jobs::find('count', [
			'conditions' => $conditions
		]);
	}

	public static function execute($worker)
	{
		try {
			Logger::info('workers', 'start executing worker', [
				'worker' => get_class($worker),
				'job id' => $worker->job()->id(),
				'job type' => $worker->job()->type,
			]);
			$worker->perform();
			self::destroy($worker);
		} catch (\Exception $e) {
			Logger::error('workers', 'error executing worker', [
				'job_id' => $worker->job()->id(),
				'job type' => $worker->job()->type,
				'job params' => $worker->job()->params,
				'exception' => get_class($e),
				'message' => $e->getMessage(),
			]);
			self::destroy($worker);
		}
	}

	public static function getNextJobWorker($priority = Worker::PRIORITY_LOW)
	{
		$job = Jobs::first([
			'order' => 'modified'
		]);
		if ($job) {
			$workerClass = 'meumobi\sitebuilder\workers\\' . \Inflector::camelize($job->type) . 'Worker';
			return new $workerClass(['job' => $job, 'logger' => Logger::logger()]);
		}
	}

	public static function destroy($worker)
	{
		Jobs::remove(['_id' => $worker->job()->id()]);
	}
}
