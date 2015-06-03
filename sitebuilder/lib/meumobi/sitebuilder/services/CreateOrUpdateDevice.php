<?php
namespace meumobi\sitebuilder\services;

use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\entities\VisitorDevice;
use app\controllers\api\InvalidArgumentException;

class CreateOrUpdateDevice
{
	public function perform($params)
	{
		$created = true;
		$errors = [];
		$data = $params['data'];
		$visitor = $params['visitor'];

		if (!isset($data['uuid']) || !$data['uuid']) {
			Logger::info('visitors', 'invalid device uuid', [
				'visitor' => $visitor->id(),
				'site' => $visitor->siteId(),
				'device data' => $data
			]);
			$created = false;
			$errors[] = 'invalid device uuid';
		} else {
			$device = $visitor->findDevice($data['uuid']);
			if ($device) {
				$device->update($data);
			} else {
				$device = new VisitorDevice($data);
				$visitor->addDevice($device);
			}
		}
		return [$created, $errors];
	}
}
