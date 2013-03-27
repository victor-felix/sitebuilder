<?php
namespace utils;

abstract class Work
{
	protected $log;
	abstract public function init();
	abstract public function run();

	public function start()
	{
		$this->log = \KLogger::instance(\Filesystem::path('log'));
		$this->init();
		$this->run();
	}
}
