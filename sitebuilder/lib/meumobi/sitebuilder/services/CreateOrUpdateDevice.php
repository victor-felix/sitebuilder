<?php
namespace meumobi\sitebuilder\services;

use app\controllers\api\InvalidArgumentException;
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

		$site_id = $site->id();
		$user_id = $user ? $user->id() : null;

		$repository = new DevicesRepository();
		$device = $repository->findForUpdate($site_id, $uuid, $user_id);

		$log = [ 'uuid' => $uuid, 'site_id' => $site_id, 'user_id' => $user_id ];

		if ($device) {
			Logger::debug(self::COMPONENT, 'device found. updating', $log);
			$device->update($data);

			$repository->update($device);

			Logger::info(self::COMPONENT, 'device updated', $log);
		} else {
			Logger::debug(self::COMPONENT, 'device not found. creating new one', $log);

			$data['uuid'] = $uuid;
			$data['site_id'] = $site_id;

			if ($user) {
				$data['user_id'] = $user_id;
			}

			$device = new Device($data);
			$repository->create($device);

			Logger::info(self::COMPONENT, 'device created', $log);
		}

		return true;
	}
}
