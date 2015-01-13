<?php

namespace meumobi\sitebuilder;

use app\models\Jobs;

class WorkerManager
{
	const LOG_CHANNEL = 'sitebuilder.worker';

	public static $logger;

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
		try {
			self::logger()->info('start executing worker', [
				'worker' => get_class($worker),
				'job id' => $worker->job()->_id,
				'job type' => $worker->job()->type,
			]);
			$worker->perform();
			self::destroy($worker);
		} catch (\Exception $e) {
			self::logger()->error('error executing worker', [
				'worker' => get_class($worker),
				'job_id' => $worker->job()->_id,
				'job type' => $worker->job()->type,
				'exception' => get_class($e),
				'message' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
			]);
		}
	}

	public static function getNextJobWorker()
	{
		$job = Jobs::first(['order' => 'modified']);
		if ($job) {
			$workerClass = 'meumobi\sitebuilder\workers\\' . \Inflector::camelize($job->type) . 'Worker';
			return new $workerClass(['job' => $job, 'logger' => self::logger()]);
		}
	}

	public static function destroy($worker)
	{
		Jobs::remove(['_id' => $worker->job()->_id]);
	}

	public static function logger()
	{
		if (self::$logger) return self::$logger;
		$handler = new \Monolog\Handler\RotatingFileHandler(APP_ROOT . '/log/works.log');
		return self::$logger = new \Monolog\Logger(self::LOG_CHANNEL, [$handler]);
	}
}
