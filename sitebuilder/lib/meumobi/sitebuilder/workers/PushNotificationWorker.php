<?php

namespace meumobi\sitebuilder\workers;

class PushNotificationWorker extends Woker
{
	public function perform()
	{
		$this->log('Sending notification');
	}
}

