<?php
namespace meumobi\sitebuilder\services;

use InvalidArgumentException;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Device;
use meumobi\sitebuilder\repositories\DevicesRepository;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\validators\ParamsValidator;

class CreateOrUpdateDevice
{
	const COMPONENT = 'devices';

	public function perform(array $params)
	{
		list($uuid, $data, $user, $site) = ParamsValidator::validate(
			$params, ['uuid', 'data', 'user', 'site']);

		$repository = new DevicesRepository();
		$device = $repository->findBySiteAndUuid($site->id(), $uuid);

		$log = [ 'uuid' => $uuid, 'site_id' => $site->id() ];

		if ($user) {
			$log['user_id'] = $user->id();
		}

		Logger::info(self::COMPONENT, 'creating or updating device', $log);

		if ($device) {
			if ($user && $user->id() != $device->userId()) {
				throw new InvalidArgumentException('device does not belong to user');
			}

			Logger::debug(self::COMPONENT, 'device found. updating', $log);
			$device->update($data);

			return $repository->update($device);
		} else {
			Logger::debug(self::COMPONENT, 'device not found. creating new one', $log);

			$data['uuid'] = $uuid;
			$data['site_id'] = $site->id();

			if ($user) {
				$data['user_id'] = $user->id();
			}

			$device = new Device($data);
			return $repository->create($device);
		}
	}
}
