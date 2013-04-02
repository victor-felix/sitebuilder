<?php

namespace utils;

use \Exception;

class Worker
{
	public $file;
	protected $log;
	protected $delay;
	protected $process;
	protected $tmpDir;

	public function __construct($process)
	{
		set_time_limit(0);
		$this->process = $process;
		$this->tmpDir = dirname(dirname(dirname(__DIR__))) . '/tmp';
		$this->log = \KLogger::instance(\Filesystem::path(APP_ROOT . '/log'));
	}

	public function canRun()
	{
		if (!$this->file) {
			$this->file = fopen($this->tmpDir . '/' . $this->process . '.pid', 'w+');
			if ($this->file && flock($this->file, LOCK_EX | LOCK_NB)) {
				return fwrite($this->file, getmypid());
			}
		}
		$this->log->logNotice('%s work: can\'t init worker. already in progress', $this->process);
		return false;
	}

	public function run()
	{
		if (!$this->canRun()) return false;

		try {
			$workClass = $this->getWorkClass();
			$work = new $workClass();
			$work->start();
		} catch (Exception $e) {
			$this->log->logError('%s work: %s', $this->process, $e->getMessage());
		}

		$this->stop();
	}

	protected function getWorkClass()
	{
		$class = ucfirst($this->process);
		require_once 'lib/utils/Works/' . $class . '.php';
		return 'utils\\'.$class;
	}

	protected function stop()
	{
		if ($this->file) {
			fclose($this->file);
			unlink($this->tmpDir . '/' . $this->process . '.pid');
		}
	}
}
