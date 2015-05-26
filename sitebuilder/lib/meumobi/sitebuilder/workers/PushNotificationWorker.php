<?php

namespace meumobi\sitebuilder\workers;

use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;//TODO move exceptions for a more generic namespace
use pushwoosh\Push;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

require_once 'lib/pushwoosh/Push.php';

class PushNotificationWorker extends Worker
{
	public function perform()
	{
		$appId = $this->getSite()->pushwoosh_app_id;
		$category = $this->getItem()->parent();
		$logData = [
			'item id' => (string)$this->getItem()->_id,
			'category id' => $category->id,
			'site id' => $this->getItem()->site_id,
		];

		if (!$appId) {
			$this->logger()->error("Push notification error: no push app configured for site", $logData);
			return true; //has no app configured
		}
		if (!$category->notification) {
			$this->logger()->error("Push notification error: push disabled on category", $logData);
			return true;
		}
		$content = $this->getItem()->title;
		$devices = $this->getDevicesTokens();
		$this->logger()->info('Sending push notification', $logData + [
			'content' => $content,
			'devices' => $devices,
		]);
		$response = Push::notify($appId, $content, $devices);
		$this->logger()->info('Push notification sent successfully', $logData + [
			'push response' => $response,
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

