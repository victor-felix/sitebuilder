<?php

namespace meumobi\sitebuilder\services;

abstract class Service
{
	const PRIORITY_LOW = 0;
	const PRIORITY_HIGH = 1;
	const LOG_CHANNEL = 'sitebuilder.service';

	protected $options;
	protected $logger;

	abstract public function call();

	public function __construct(array $options = []) {
		$this->options = $options;
	}

	// TODO this does not belong on a generic Service class. move it somewhere else
	protected function priorityCriteria()
	{
		$priorities = [
		self::PRIORITY_HIGH => ['$gte' => 1],
		self::PRIORITY_LOW => ['$exists' => false],
		];

		return $priorities[$this->options['priority']];
	}

	// TODO we might inject a logger into the services if we want, but otherwise
	// we should delegate all this responsibility to an external class, possibly
	// static, so we can call log() easily from anywhere in our code
	protected function logger()
	{
		if ($this->logger) return $this->logger;

		if (isset($this->options['logger'])) {
			return $this->logger = $this->options['logger'];
		}

		$handler = new \Monolog\Handler\RotatingFileHandler($this->loggerPath());
		$logger = new \Monolog\Logger(static::LOG_CHANNEL, [$handler]);

		return $this->logger = $logger;
	}

	protected function loggerPath()
	{
		$path = isset($this->options['logger_path']) ? $this->options['logger_path'] : 'log/log';
		return APP_ROOT . '/' . $path;
	}
}
