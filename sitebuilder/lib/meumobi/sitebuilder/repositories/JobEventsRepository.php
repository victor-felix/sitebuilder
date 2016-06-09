<?php

namespace meumobi\sitebuilder\repositories;

use MongoDate;

class JobEventsRepository extends Repository
{
	protected $collectionName = 'job_events';

	public function createOrUpdate($params)
	{
		$start = new MongoDate($params['start']->getTimestamp());
		$end = new MongoDate($params['end']->getTimestamp());

		$criteria = ['worker' => $params['worker']];

		if (isset($params['params'])) {
			$criteria += $params['params'];
		}

		$data = compact('start', 'end') + $criteria;

		$result = $this->collection()->update($criteria, $data, [ 'upsert' => true ]);

		if ($result['ok']) {
			return [
				'success' => true,
				'created' => !!$result['nModified'],
			];
		}
	}
}
