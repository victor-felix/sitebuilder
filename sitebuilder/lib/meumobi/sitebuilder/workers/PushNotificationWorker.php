<?php

namespace meumobi\sitebuilder\workers;

use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;//TODO move exceptions for a more generic namespace

class PushNotificationWorker extends Worker
{
	protected $item;

	public function perform()
	{
		$this->logger()->info('Sending notification for item ' . $this->getItem()->id());
	}

	protected function getItem()
	{
		if ($this->item) return $this->item;
		$this->item = Items::find('type', array('conditions' => array(
			'_id' => $this->job()->params['item_id']
		)));
		if (!$this->item) {
			throw new RecordNotFoundException("The item '{$id}' was not found"); 
		}
		return $this->item;
	}
}

