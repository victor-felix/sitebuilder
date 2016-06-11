<?php

namespace meumobi\sitebuilder\repositories;

use DateTime;
use MongoDate;

class JobEventsRepository extends Repository
{
	protected $requiredParams = ['worker', 'start', 'end'];
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

	public function getStatus($jobs)
	{
		$jobEvents = $this->collection()->find([
			'worker' => ['$in' => $jobs]
		]);
		$jobEvents = iterator_to_array($jobEvents->sort([
			'worker' => 1,
			'priority' => 1,
		]));

		$self = $this;
		$oneHourAgo = time() - 3600;

		return array_map(function($ev) use ($self, $oneHourAgo) {
			return $ev + [
				'ok' => $ev['end']->sec > $oneHourAgo,
			];
		}, $jobEvents);
	}
}
