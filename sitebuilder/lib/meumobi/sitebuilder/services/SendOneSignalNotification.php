<?php

namespace meumobi\sitebuilder\services;

use Config;
use OneSignal\Config;
use OneSignal\OneSignal;
use meumobi\sitebuilder\validators\ParamsValidator;

class SendOneSignalNotification
{
	const COMPONENT = 'pushnotif_onesignal';

	public function perform(array $app, array $notif)
	{
		list($appId, $appAuthToken) = ParamsValidator::validate($app,
			['appId', 'appAuthToken']);

		$config = new Config();
		$config->setApplicationId($appId);
		$config->setApplicationAuthKey('your_application_auth_key');
		$config->setUserAuthKey(Config::read('OneSignal.authToken'));

		$api = new OneSignal($config);

		$notification = $this->notification($notif);
		$api->notifications->add($notification);

		return [];
	}

	protected function notification(array $options)
	{
		list($header, $content, $banner, $icon, $data, $devices) =
			ParamsValidator::validate($options, [
				'header', 'content', 'banner', 'icon', 'data', 'devices'
			]);

		$notification = [
			'headings' => [ 'en' => $header ],
			'contents' => [ 'en' => $content ],
			'data' => $data,
			'ios_badgeType' => 'Increase',
			'ios_badgeCount' => 1,
		];

		if ($devices) {
			$notification['include_player_ids'] = $devices;
		}

		if ($banner) {
			$notification['large_picture'] = $banner;
		}

		if ($icon) {
			$notification['large_icon'] = $icon;
		}

		return $notification;
	}
}
