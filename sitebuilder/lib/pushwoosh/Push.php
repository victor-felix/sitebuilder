<?php
namespace pushwoosh;

Use Config;
Use Exception;
use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Notification\Android;
use Gomoob\Pushwoosh\Model\Notification\IOS;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class Push
{
	protected static $client;

	public static function notify(array $options)
	{
		list($icon, $header, $content, $appId, $devices) = ParamsValidator::validate(
			$options, ['icon', 'header', 'content', 'appId', 'devices']);

		// CreateMessageRequest:
		// http://gomoob.github.io/php-pushwoosh/create-message.html
		$request = CreateMessageRequest::create()
			->addNotification(static::getNotification($content, $icon, $header, $devices));

		$response = static::getClient($appId)->createMessage($request);

		Logger::debug('push_notification', 'payload request', $request->toJSON());

		if ($response->isOk()) {
			return [
				'status_code' => $response->getStatusCode(),
				'status_message' => $response->getStatusMessage()
			];
		} else {
			throw new Exception("Error sending push notification, "
				. "status_code: {$response->getStatusCode()}, "
				. "status_message: {$response->getStatusMessage()}"
			);
		}
	}

	public static function getNotification($content, $icon, $header, $devices)
	{
		$notification = Notification::create()
			->setContent($content)
			->setIOS(IOS::create()
				->setBadges('+1')
			)
			->setAndroid(Android::create()
				->setIcon($icon)
				->setHeader($header)
			);

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
