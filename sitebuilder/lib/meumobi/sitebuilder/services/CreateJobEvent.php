<?php

namespace meumobi\sitebuilder\services;

use meumobi\sitebuilder\repositories\JobEventsRepository;

class CreateJobEvent
{
	public function perform($params)
	{
		$repo = new JobEventsRepository;
		$repo->createOrUpdate($params);
	}
}
