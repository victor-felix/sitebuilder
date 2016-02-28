<?php

namespace app\controllers\api;

use MongoDate;
use app\models\Items;
use meumobi\sitebuilder\repositories\PollsRepository;
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
		$values = $this->request->get('data:value');

		if (!$user || !$item || $item->type() != 'Poll') {
			throw new RecordNotFoundException('poll not found');
		}

		if (!is_array($values) || empty($values)) {
			return ['errors' => ['vote has not been provided']];
		}

		$votes = 0;

		foreach ($values as $key => $value) {
			if ($votes >= 1 && !$item->multiple_choices) {
				return ['errors' => ['this is not a multiple choice poll']];
			}

			if (isset($options[$key]) && !empty($options[$key])) {
				$values[$key] = 1;
				$votes += 1;
			} else {
				return ['errors' => ["invalid option '$key'"]];
			}
		}

		$vote = [
			'user_id' => $user->id(),
			'values' => $values,
			'timestamp' => new MongoDate(),
		];

		$results = $item->results ?: [];
		$results []= $vote;

		$repo = new PollsRepository();
		$repo->addVote($item, $vote);

		unset($item['results']);
		$item->set([ 'results' => $results ]);

		return $item->toJSON($user);
	}
}
