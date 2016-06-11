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
		$params['worker'] = join('', array_slice(explode('\\', $params['worker']), -1));

		if ($result = $repo->createOrUpdate($params)) {
			Logger::info(self::COMPONENT, 'job event created', $params + $result);
		} else {
			Logger::error(self::COMPONENT, 'job event not created', $params);
		}
	}
}
