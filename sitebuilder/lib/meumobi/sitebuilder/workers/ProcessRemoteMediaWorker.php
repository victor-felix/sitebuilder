<?php

namespace meumobi\sitebuilder\workers;

use meumobi\sitebuilder\services\ProcessRemoteMedia;

class ProcessRemoteMediaWorker extends Worker
{
	public function perform()
	{
		$service = new ProcessRemoteMedia;
		$service->perform($this->getItem());
	}
}
