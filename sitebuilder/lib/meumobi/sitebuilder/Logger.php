<?php

namespace meumobi\sitebuilder;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use meumobi\sitebuilder\logger\LineFormatter;

class Logger
{
	protected static $instance;
	protected $logger;

	public static function instance()
	{
		if (!self::$instance) self::$instance = new self;

		return self::$instance;
	}

	public static function logger()
	{
		return self::instance()->getLogger();
	}

	// all static calls just redirect to the singleton
	public static function emergency($component, $message, array $context = [])
	{
		return self::log(LogLevel::EMERGENCY, $component, $message, $context);
	}

	public static function alert($component, $message, array $context = [])
	{
		return self::log(LogLevel::ALERT, $component, $message, $context);
	}

	public static function critical($component, $message, array $context = [])
	{
		return self::log(LogLevel::CRITICAL, $component, $message, $context);
	}

	public static function error($component, $message, array $context = [])
	{
		return self::log(LogLevel::ERROR, $component, $message, $context);
	}

	public static function warning($component, $message, array $context = [])
	{
		return self::log(LogLevel::WARNING, $component, $message, $context);
	}

	public static function notice($component, $message, array $context = [])
	{
		return self::log(LogLevel::NOTICE, $component, $message, $context);
	}

	public static function info($component, $message, array $context = [])
	{
		return self::log(LogLevel::INFO, $component, $message, $context);
	}

	public static function debug($component, $message, array $context = [])
	{
		return self::log(LogLevel::DEBUG, $component, $message, $context);
	}

	public static function log($level, $component, $message, array $context = [])
	{
		$context['component'] = $component;
		return self::logger()->log($level, $message, $context);
	}

	public function __construct(LoggerInterface $logger = null)
	{
		if (!$logger) {
			$formatter = new \Monolog\Formatter\LineFormatter();
			$formatter->ignoreEmptyContextAndExtra();
			$handler = new \Monolog\Handler\StreamHandler(APP_ROOT . '/log/sitebuilder.log');
			$handler->setFormatter($formatter);
			$logger = new \Monolog\Logger('sitebuilder', [$handler]);
		}

		$this->logger = $logger;
	}

	public function getLogger() {
		return $this->logger;
	}
}
