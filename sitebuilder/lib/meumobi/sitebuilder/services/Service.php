<?php
namespace meumobi\sitebuilder\services;
use Exception;

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

	protected function priorityCriteria()
	{
		$priorities = [
		self::PRIORITY_HIGH => ['$gte' => 1],
		self::PRIORITY_LOW => ['$exists' => false],
		];

		return $priorities[$this->options['priority']];
	}

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
		return APP_ROOT . '/' . $this->options['logger_path'];
	}
}
