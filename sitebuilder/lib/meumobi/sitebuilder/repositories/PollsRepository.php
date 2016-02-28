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
}
