<?php
namespace pushwoosh;

use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;
use Gomoob\Pushwoosh\Model\Notification\IOS;

class Push
{
	protected static $client;

	public static function notify($appId, $content, $devices)
	{
		$request = CreateMessageRequest::create()
		->addNotification(static::getNotification($content, $devices));
		$response = static::getClient($appId)->createMessage($request);
		if ($response->isOk()) {
			return [
				'status code' => $response->getStatusCode(),
				'status message' => $response->getStatusMessage()
			];
		} else {
			//TODO use a specific Exception
			throw new Exception("Error sending push notification,
			status code : {$response->getStatusCode()},
			status message: {$response->getStatusMessage()}");
		}
	}

	public static function getNotification($content, $devices)
	{
		$notification = Notification::create()->setContent($content);
		//add badge
		$notification->setIOS(IOS::create()->setBadges(1));
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
