<?php

namespace meumobi\sitebuilder\repositories;

use MongoDate;
use MongoId;

class PollsRepository extends Repository
{
	protected $collectionName = 'items';

	public function addVote($item, $vote)
	{
		$criteria = ['_id' => new MongoId($item->id())];
		$data = ['$push' => ['results' => $vote]];

		if ($this->collection()->update($criteria, $data)) {
			return true;
		}

		return false;
	}

	public function modifyVote($item, $vote)
	{
		$criteria = [
			'_id' => new MongoId($item->id()),
			'results.user_id' => $vote['user_id'],
		];

		$data = ['$set' => ['results.$' => $vote]];

		if ($this->collection()->update($criteria, $data)) {
			return true;
		}
	}

	public function findVotes($item)
	{
		$criteria = ['_id' => new MongoId($item->id())];
		$item = $this->collection()->findOne($criteria);

		return $item ? $this->hydrateVoteSet($item['results']) : null;
	}

	protected function hydrateVote($vote)
	{
		return [
			'user_id' => $vote['user_id'],
			'values' => $vote['values'],
			'timestamp' => $vote['timestamp']->sec,
		];
	}

	protected function hydrateVoteSet($set)
	{
		return array_map(function($data) {
			return $this->hydrateVote($data);
		}, $set);
	}
}
