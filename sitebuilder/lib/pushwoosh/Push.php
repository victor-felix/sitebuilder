<?php
namespace pushwoosh;

Use Config;
Use Exception;
use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Notification\IOS;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;
use meumobi\sitebuilder\Logger;

class Push
{
	protected static $client;

	public static function notify($appId, $content, $devices)
	{
		$request = CreateMessageRequest::create()
		->addNotification(static::getNotification($content, $devices));

		$response = static::getClient($appId)->createMessage($request);

		Logger::debug('push_notification', 'payload request', $request->toJSON());

		if ($response->isOk()) {
			return [
				'status_code' => $response->getStatusCode(),
				'status_message' => $response->getStatusMessage()
			];
		} else {
			throw new Exception("Error sending push notification, status_code: {$response->getStatusCode()}, status_message: {$response->getStatusMessage()}");
		}
	}

	public static function getNotification($content, $devices)
	{
		$notification = Notification::create()->setContent($content);
		//add badge
		$notification->setIOS(IOS::create()->setBadges('+1'));
		if ($devices) $notification->setDevices($devices);
		return $notification;
	}

	public static function getClient($app)
	{
		if (static::$client) return static::$client;
		return static::$client = Pushwoosh::create()
		->setApplication($app)
		->setAuth(Config::read('PushWoosh.authToken'));
	}
}
