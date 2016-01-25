<?php

namespace meumobi\sitebuilder\workers;

require_once 'lib/pushwoosh/Push.php';

use app\models\Items;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\RecordNotFoundException;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use pushwoosh\Push;

class PushNotificationWorker extends Worker
{
	const COMPONENT = 'pushnotif';

	public function perform()
	{
		$site = $this->getSite();
		$appId = $site->pushwoosh_app_id;
		$item = $this->getItem();
		$category = $item->parent();
		$content = $item->title;
		$devices = $this->getDevicesTokens($item);

		$log = [
			'item_id' => $item->id(),
			'category_id' => $category->id,
			'site_id' => $site->id,
		];

		if (!$appId || !$category->notification || !$devices) {
			return;
		}

		Logger::info(self::COMPONENT, 'sending push notification', $log + [
			'content' => $content,
			'number_of_devices' => count($devices),
		]);

		$response = Push::notify([
			'icon' => $site->appleTouchIcon()->link('72x72'),
			'header' => $site->title,
			'content' => $content,
			'appId' => $appId,
			'devices' => $devices,
		]);

		Logger::info(self::COMPONENT, 'push notification sent successfully', $log + [
			'push_response' => $response,
		]);
	}

	// TODO use the new devices repository
	protected function getDevicesTokens($item)
	{
		$repository = new VisitorsRepository();
		$groups = $item->to('array')['groups'];

		if ($groups) {
			$visitors = $repository->findBySiteIdAndGroups($this->getSite()->id, $groups);
		} else {
			$visitors = $repository->findBySiteId($this->getSite()->id);
		}

		return array_reduce($visitors, function($tokens, $visitor) {
			$visitorTokens = [];

			foreach ($visitor->devices() as $device) {
				if ($device->pushId()) $visitorTokens[] = $device->pushId();
			}

			return array_merge($tokens, $visitorTokens);
		}, []);
	}
}

