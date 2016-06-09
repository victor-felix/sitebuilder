<?php

namespace meumobi\sitebuilder\services;

use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\repositories\JobEventsRepository;

class CreateJobEvent
{
	const COMPONENT = 'job_event';

	public function perform($params)
	{
		$repo = new JobEventsRepository;
		if ($result = $repo->createOrUpdate($params)) {
			Logger::info(self::COMPONENT, 'job event created', $params + $result);
		} else {
			Logger::error(self::COMPONENT, 'job event not created', $params);
		}
	}
}
