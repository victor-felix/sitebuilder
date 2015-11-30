<?php

namespace meumobi\sitebuilder;

use Exception;
use Inflector;
use app\models\Jobs;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\workers\Worker;

class WorkerManager
{
	const COMPONENT = 'worker_manager';

	public static function enqueue($type, $params)
	{
		$job = Jobs::create([
			'type' => $type,
			'params' => $params
		]);

		$job->save();

		Logger::info(self::COMPONENT, 'job created', [
			'job_id' => $job->id(),
			'type' => $type,
			'params' => $params,
		]);

		return $job;
	}

	public static function execute($worker)
	{
		try {
			Logger::info(self::COMPONENT, 'start executing worker', [
				'job_id' => $worker->job()->id(),
				'job_type' => $worker->job()->type,
				'job_params' => $worker->job()->params,
			]);

			$worker->perform();

			self::destroy($worker);

			Logger::info(self::COMPONENT, 'worker finished', [
				'job_id' => $worker->job()->id(),
				'job_type' => $worker->job()->type,
				'job_params' => $worker->job()->params,
			]);
		} catch (Exception $e) {
			Logger::error(self::COMPONENT, 'error executing worker', [
				'job_id' => $worker->job()->id(),
				'job_type' => $worker->job()->type,
				'job_params' => $worker->job()->params,
				'exception' => $e,
				'message' => $e->getMessage(),
			]);

			self::destroy($worker);
		}
	}

	public static function getNextJobWorker()
	{
		$job = Jobs::first([ 'order' => 'modified' ]);

		if ($job) {
			$workerClass = 'meumobi\sitebuilder\workers\\' .
				Inflector::camelize($job->type) . 'Worker';

			return new $workerClass([
				'params' => $job->params,
				'job' => $job,
				'logger' => Logger::logger(),
			]);
		}
	}

	public static function destroy($worker)
	{
		Jobs::remove(['_id' => $worker->job()->id()]);
	}
}
