<?php

namespace meumobi\sitebuilder\services;

use Config;
use MeuMobi;
use app\models\Items;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\repositories\DevicesRepository;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\services\SendOneSignalNotification;
use meumobi\sitebuilder\services\SendPushwooshNotification;

class SendPushNotification
{
	const COMPONENT = 'pushnotif';

	public function perform($item)
	{
		$site = $item->site();
		$category = $item->parent();
		$services = [];

		if (!$category->notification) {
			return;
		}

		if ($this->isSetOneSignalSettings($site)) {
			$services[] = [
				'provider' => 'onesignal',
				'service' => new SendOneSignalNotification,
				'appConfig' => [
					'appId' => $site->pushnotif_app_id,
					'appAuthToken' => $site->pushnotif_app_auth_token,
				],
				'devices' => $site->private
					? $this->getDevicesTokens($item, $site, 'onesignal')
					: null
			];
		}

		if ($this->isSetPushwooshSettings($site)) {
			$config = $this->getPushwooshSettings($site);
			$services[] = [
				'provider' => 'pushwoosh',
				'service' => new SendPushwooshNotification,
				'appConfig' => [
					'appId' => $config['app_id']
				],
				'devices' => $site->private
					? $this->getDevicesTokens($item, $site, 'pushwoosh')
					: null
			];
		}

		$log = [
			'item_id' => $item->id(),
			'category_id' => $category->id,
			'site_id' => $site->id,
		];

		Logger::info(self::COMPONENT, 'sending push notification', $log + [
			'content' => $item->title,
			'number_of_devices' => 'all avaiable',
		]);		

		$icon = $site->appleTouchIcon()
			? MeuMobi::url($site->appleTouchIcon()->link('72x72'), true)
			: null;

		$banner = $item->images()
			? MeuMobi::url($item->images()[0]->link('314x220'), true)
			: null;

		$notif = [
			'header' => $site->title,
			'content' => $item->title,
			'banner' => $banner,
			'icon' => $icon,
			'data' => [
				'item_id' => $item->id(),
				'category_id' => $item->parent_id,
			]
		];

		Logger::info(self::COMPONENT, 'delegating to push notif services', $log + [
			'parameters' => $notif,
		]);

		foreach($services as $serviceData){
			Logger::info(self::COMPONENT, 'calling the push notif service:' + ' ' + $serviceData['provider']);
			$app = $serviceData['appConfig'];
			$notifData = $notif + [ 'devices' => $serviceData['devices'] ];
			$service = $serviceData['service'];

			$notification_response = $service->perform($app, $notifData);

			if ($notification_response !== false) {
				if ($serviceData['provider'] == 'onesignal' && isset($notification_response['notification_id'])) {
					$item->notification_id = $notification_response['notification_id'];
					$item->save();
				}
				Logger::info(self::COMPONENT, 'push notification sent', $log + [
					'provider' => $serviceData['provider']
				]);
			}
		}

	}

	protected function isSetOneSignalSettings($site)
	{
		if ($site->pushnotif_service == 'onesignal' 
			&& $site->pushnotif_app_id 
			&& $site->pushnotif_app_auth_token){
			return true;
		}
		return false;
	}

	protected function isSetPushwooshSettings($site)
	{
		$settings = $this->getPushwooshSettings($site);
		return !empty($settings);
	}

	protected function getPushwooshSettings($site)
	{
		$appIds = Config::read('PushWoosh.appIds');

		$filteredIds = array_filter($appIds, function($appId) use ($site) {
			return $appId['site_id'] == $site->id;
		});

		return !empty($filteredIds)
			? $filteredIds[0]
			: [];
	}

	protected function getDevicesTokens($item, $site, $push_service = 'onesignal')
	{
		$visitors = null;

		if ($site->private) {
			$groups = $item->to('array')['groups'];
			$visitorsRepo = new VisitorsRepository();
			$visitors = $groups
				? array_map(function($v) {
					return $v->id();
				}, $visitorsRepo->findBySiteIdAndGroups($site->id, $groups))
				: null;
		}

		$repository = new DevicesRepository();
		$devices = $repository->findForPushNotif($site->id, $visitors);

		if ($push_service == 'onesignal') {
			return array_map(function($device) { return $device->playerId(); }, $devices);
		}

		return array_map(function($device) { return $device->pushId(); }, $devices);
	}
}
