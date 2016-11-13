<?php

namespace meumobi\sitebuilder\services;

use Config;
use Exception;
use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Notification\Android;
use Gomoob\Pushwoosh\Model\Notification\IOS;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class SendPushwooshNotification
{
	const COMPONENT = 'pushnotif_pushwoosh';

	public function perform(array $app, array $notif)
	{
		list($appId, $appAuthToken) = ParamsValidator::validate($app,
			['appId', 'appAuthToken']);

		$client =  Pushwoosh::create()
			->setApplication($appId)
			->setAuth(Config::read('PushWoosh.authToken'));

		$notification = $this->notification($notif);
		$request = CreateMessageRequest::create()
			->addNotification($notification);

		Logger::debug(self::COMPONENT, 'payload request', $request->toJSON());

		$response = $client->createMessage($request);

		if (!$response->isOk()) {
			Logger::error(self::COMPONENT, 'push notification not sent', [
				'app' => $app,
				'notifcation' => $notif,
				'status_code' => $response->getStatusCode(),
				'status_message' => $response->getStatusMessage(),
			]);

			return false;
		}

		return true;
	}

	protected function notification(array $options)
	{
		list($header, $content, $banner, $icon, $data, $devices) =
			ParamsValidator::validate($options, [
				'header', 'content', 'banner', 'icon', 'data', 'devices'
			]);

		$android = Android::create()
			->setHeader($header)
			->setBadges('+1');

		$ios = IOS::create()
			->setBadges('+1');

		if ($icon) {
			$android
				->setIcon($icon)
				->setCustomIcon($icon);
		}

		if ($banner) {
			$android->setBanner($banner);
		}

		$notification = Notification::create()
			->setContent($content)
			->setData($data)
			->setIOS($ios)
			->setAndroid($android);

		if ($devices) $notification->setDevices($devices);

		return $notification;
	}

	protected function client($app, $authToken)
	{
		return Pushwoosh::create()
			->setApplication($app)
			->setAuth($authToken);
	}
}
