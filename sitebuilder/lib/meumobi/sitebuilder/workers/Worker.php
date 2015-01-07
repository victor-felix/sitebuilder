<?php

namespace meumobi\sitebuilder\workers;

abstract class Worker
{
	const LOG_CHANNEL = 'sitebuilder.worker';

	protected $logger;
	protected $job;

	abstract public function perform();

	public function __construct(array $attrs = [])
	{
		$this->setAttributes($attrs);
	}

	public function setAttributes(array $attrs)
	{
		foreach ($attrs as $key => $value) {
			if (is_string($value)) $value = trim($value);
			$key = Inflector::camelize($key, false);
			$method = 'set' . Inflector::camelize($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			} else if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	public function job()
	{
		return $this->job;
	}

	protected function logger()
	{
		if ($this->logger) return $this->logger;

		$handler = new \Monolog\Handler\RotatingFileHandler(APP_ROOT . '/log/feeds.log');
		$logger = new \Monolog\Logger(static::LOG_CHANNEL, [$handler]);

		return $this->logger = $logger;
	}
}

