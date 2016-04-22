<?php

namespace meumobi\sitebuilder\workers;

use meumobi\sitebuilder\services\SendPushNotification;

class PushNotificationWorker extends Worker
{
	public function perform()
	{
		$service = new SendPushNotification;
		$service->perform($this->getItem());
	}
}
