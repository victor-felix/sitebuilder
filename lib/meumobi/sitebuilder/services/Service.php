<?php
namespace meumobi\sitebuilder\services;

abstract class Service
{
	const PRIORITY_LOW = 0;
	const PRIORITY_HIGH = 1;

	protected $options;
	protected $logger;

	abstract public function call();

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
		$logger = new \Monolog\Logger('sitebuilder.merchant_products',
				[$handler]);

		return $this->logger = $logger;
	}

	protected function loggerPath()
	{
		return APP_ROOT . '/' . $this->options['logger_path'];
	}
}