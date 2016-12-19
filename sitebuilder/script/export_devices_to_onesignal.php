<?php

use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Device;
use meumobi\sitebuilder\repositories\DevicesRepository;
use OneSignal\OneSignal;
use OneSignal\Devices;
use OneSignal\Config as OneSignalConfig;
use OneSignal\Exception\OneSignalException;

require dirname(__DIR__) . '/config/cli.php';

class ExportDevicesToOneSignal {

	const COMPONENT = 'export_devices_onesignal';

	public function perform($siteId) {
		$site = Model::load('Sites')->firstById($siteId);
		$repo = new DevicesRepository();
		if (!$site->pushnotif_app_id || $site->pushnotif_service != 'onesignal') {
			return false;
		}

		$appId = $site->pushnotif_app_id;
		$appAuthToken = $site->pushnotif_app_auth_token;

		$config = new OneSignalConfig();
		$config->setApplicationId($appId);
		$config->setApplicationAuthKey($appAuthToken);
		$config->setUserAuthKey(Config::read('OneSignal.authToken'));

		$api = new OneSignal($config);

		$devices = $repo->findForExport($siteId);

		foreach ($devices as $device) {
			$deviceData = [
				'identifier' => $device->pushId(),		
				'device_model' => $device->model(),
				'device_os' => $device->platformVersion(),
				'game_version' => $device->appVersion(),
			];
			if ($device->playerId()) {
				Logger::info(self::COMPONENT, 'playerId already exists: Updating the device data');
				$response = $api->devices->update($device->playerId(), $deviceData);
			}
			else {
				Logger::info(self::COMPONENT, 'playerId did not exists: Adding the device');
				$deviceData['device_type'] =
					(strtolower($device->platform()) == 'android') ? 
					Devices::ANDROID:
					Devices::IOS;
				$response = $api->devices->add($deviceData);
				$device->update([
							'player_id' => $response['id']
						]);
				$repo->update($device);
			}
		}
	}
}

//TODO Analyze if this script should become a worker
$options = getopt('s:');
$siteId = $options['s'];
(new ExportDevicesToOneSignal())->perform($siteId);