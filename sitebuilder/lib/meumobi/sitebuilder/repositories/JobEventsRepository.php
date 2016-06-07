<?php

namespace meumobi\sitebuilder\repositories;

use MongoDate;

class JobEventsRepository extends Repository
{
	public function createOrUpdate($params)
	{
		$start = new MongoDate($data['start']->toDateTime());
		$end = new MongoDate($data['end']->toDateTime());

		$criteria = ['worker' => $data['worker']];

		if (isset($params['params'])) {
			$criteria += $params['params'];
		}

		$data = compact('start', 'end') + $criteria;

		$this->collection()->update($criteria, $data, [ 'upsert' => true ]);
	}
}
