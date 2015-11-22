<?php

namespace meumobi\sitebuilder\workers;

use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\services\MediaThumbnailer;

class MediaThumbnailerWorker extends Worker
{
	public function perform()
	{
		$item = $this->getItem();
		$mediaThumbnailer = new MediaThumbnailer;
		$mediaThumbnailer->perform($item);
	}
}
