<?php

namespace meumobi\sitebuilder\workers;

require_once 'lib/pushwoosh/Push.php';

use app\models\Items;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\RecordNotFoundException;//TODO move exceptions for a more generic namespace
use meumobi\sitebuilder\repositories\VisitorsRepository;
use pushwoosh\Push;

class PushNotificationWorker extends Worker
{
	public function perform()
	{
		$appId = $this->getSite()->pushwoosh_app_id;
		$category = $this->getItem()->parent();
		$logData = [
			'item_id' => (string)$this->getItem()->_id,
			'category_id' => $category->id,
			'site_id' => $this->getItem()->site_id,
		];

		if (!$appId) {
			Logger::info('push_notification', 'no push app configured for site', $logData);
			return true; //has no app configured
		}
		if (!$category->notification) {
			Logger::info('push_notification', 'push disabled on category', $logData);
			return true;
		}
		$content = $this->getItem()->title;
		$devices = $this->getDevicesTokens();
		Logger::info('push_notification', 'sending push notification', $logData + [
			'content' => $content,
			'number_of_devices' => count($devices),
		]);
		$response = Push::notify($appId, $content, $devices);
		Logger::info('push_notification', 'push notification sent successfully', $logData + [
			'push_response' => $response,
		]);
	}

	protected function getDevicesTokens()
	{
		$repository = new VisitorsRepository();
		$groups = $this->getItem()->to('array')['groups'];//return Document object on direct access
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
		},[]);
	}
}

