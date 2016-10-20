<?php

namespace meumobi\sitebuilder\services;

use OneSignal\Config;
use OneSignal\Devices;
use OneSignal\OneSignal;
use meumobi\sitebuilder\validators\ParamsValidator;

class SendOneSignalNotification
{
	const COMPONENT = 'pushnotif_onesignal';

	public function perform(array $auth, array $notif)
	{
	}
}
