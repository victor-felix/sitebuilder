<?php

namespace meumobi\sitebuilder\workers;

require_once 'lib/pushwoosh/Push.php';

use app\models\Items;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\repositories\DevicesRepository;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use pushwoosh\Push;

class PushNotificationWorker extends Worker
{
	const COMPONENT = 'pushnotif';

	public function perform()
	{
		$site = $this->getSite();
		$item = $this->getItem();
		$category = $item->parent();
		$devices = $this->getDevicesTokens($item);

		if (!$site->pushwoosh_app_id || !$category->notification || !$devices) {
			return;
		}

		$log = [
			'item_id' => $item->id(),
			'category_id' => $category->id,
			'site_id' => $site->id,
		];

		Logger::info(self::COMPONENT, 'sending push notification', $log + [
			'content' => $item->title,
			'number_of_devices' => count($devices),
		]);

		$response = Push::notify([
			'site' => $site,
			'item' => $item,
			'devices' => $devices,
		]);

		Logger::info(self::COMPONENT, 'push notification sent successfully', $log + [
			'push_response' => $response,
		]);
	}

	protected function getDevicesTokens($item)
	{
		$site = $this->getSite();
		$visitors = null;

		if ($site->private) {
			$groups = $item->to('array')['groups'];
			$visitorsRepo = new VisitorsRepository();
			$visitors = $groups
				? $visitorsRepo->findBySiteIdAndGroups($site->id, $groups)
				: null;
		}

		$repository = new DevicesRepository();
		$devices = $repository->findForPushNotif($site->id, $visitors);

		return array_map(function($device) { return $device->pushId(); }, $devices);
	}
}
