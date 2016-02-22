<?php

namespace app\controllers\api;

use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;

class PollsController extends ApiController
{
	public function vote()
	{
		$user = $this->checkVisitor();
		$item = Items::find('type', ['conditions' => [
			'_id' => $this->request->params['item_id'],
			'site_id' => $this->site()->id
		]]);

		if (!$user || !$item || $item->type() != 'Poll') {
			throw new RecordNotFoundException('poll not found');
		}

		return $item->toJSON($user);
	}
}
