<?php
namespace meumobi\sitebuilder\services;

use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Device;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\validators\ParamsValidator;

class CreateOrUpdateDevice
{
	const COMPONENT = 'devices';

	public function perform(array $params)
	{
		list($uuid, $data, $user) = ParamsValidator::validate($params, ['uuid',
			'data', 'user']);

		$device = $user->findDevice($uuid);

		$log = [
			'uuid' => $uuid,
			'user_id' => $user->id(),
		];

		Logger::info(self::COMPONENT, 'creating or updating device', $log);

		if ($device) {
			Logger::debug(self::COMPONENT, 'device found. updating', $log);
			$device->update($data);
		} else {
			Logger::debug(self::COMPONENT, 'device not found. creating new one', $log);

			$data['uuid'] = $uuid;
			$data['user_id'] = $user->id();

			$device = new Device($data);
			$visitor->addDevice($device);
		}

		Logger::debug(self::COMPONENT, 'updating visitor with device information', $log);

		$repository = new VisitorsRepository();
		return $repository->update($user);
	}
}
