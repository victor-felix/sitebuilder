<?php
namespace pushwoosh;

use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;

class Push
{
	protected static $client;

	public static function notify($content, $devices)
	{
		$request = CreateMessageRequest::create()
		->addNotification(static::getNotification($content, $devices));
		$response = static::getClient('E76A0-70562')->createMessage($request);
		if ($response->isOk()) {
			return true;
		} else {
			//TODO use a specific Exception
			throw new Exception("Error sending push notification,
			code : {$response->getStatusCode()},
			message: {$response->getStatusMessage()}");
		}
	}

	public static function getNotification($content, $devices)
	{
		$notification = Notification::create()->setContent($content);
		if ($devices) $notification->setDevices($devices);
		return $notification;
	}

	public static function getClient($app)
	{
		if (static::$client) return static::$client;
		return static::$client = Pushwoosh::create()
		->setApplication($app)
		->setAuth('z8slYDk24hm2SJDIhzi6SBcdFPjCMU870gEH4wJ9WbzcdJsC6RBVl72r7k12b99yoHxZ39VDoOPYNsoLLtRk');
	}
}
