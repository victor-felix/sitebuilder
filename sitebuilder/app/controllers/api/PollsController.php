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
		$item = $this->findItem($this->request->params['item_id']);
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

		// this is a dummy option so PHP understands that we actually want an
		// associative array, not a numeric index one. that's important so we
		// can serialize to mongodb and in the API correctly
		$values['_'] = 0;

		$vote = [
			'user_id' => $user->id(),
			'values' => $values,
			'timestamp' => new MongoDate(),
		];

		$repo = new PollsRepository();

		if ($item->userVote($user)) {
			$repo->modifyVote($item, $vote);
		} else {
			$repo->addVote($item, $vote);
		}

		return $this->findItem($item->id())->toJSON($user);
	}

	protected function findItem($id)
	{
		return Items::find('type', ['conditions' => [
			'_id' => $id,
			'site_id' => $this->site()->id
		]]);
	}
}
