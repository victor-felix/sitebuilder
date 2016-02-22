<?php

namespace app\controllers\api;

use MongoDate;
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
		$options = $item->options->to('array');
		$values = $this->request->get('data:values');

		if (!$user || !$item || $item->type() != 'Poll') {
			throw new RecordNotFoundException('poll not found');
		}

		if (!is_array($values) || empty($values)) {
			return ['errors' => ['vote has not been provided']];
		}

		$results = $item->results ?: [];

		foreach ($values as $key => $value) {
			if (!isset($options[$key])) {
				return ['errors' => ["invalid option '$key'"]];
			}
		}

		$results []= [
			'user_id' => $user->id(),
			'values' => $this->request->get('data:values'),
			'timestamp' => new MongoDate(),
		];

		unset($item['results']);
		$item->set([ 'results' => $results ]);
		$item->save();

		return $item->toJSON($user);
	}
}
