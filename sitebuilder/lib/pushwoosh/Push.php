<?php
namespace pushwoosh;

use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;

class Push
{
	protected static $client;

	public static function notify($appId, $content, $devices)
	{
		$request = CreateMessageRequest::create()
		->addNotification(static::getNotification($content, $devices));
		$response = static::getClient($appId)->createMessage($request);
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
		->setAuth(\Config::read('PushWoosh.authToken'));
	}
}
