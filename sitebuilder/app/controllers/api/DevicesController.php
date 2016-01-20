<?php

namespace app\controllers\api;

use app\controllers\api\InvalidArgumentException;
use meumobi\sitebuilder\services\CreateOrUpdateDevice;

class DevicesController extends ApiController
{
	protected $skipBeforeFilter = ['requireVisitorAuth'];

	public function update()
	{
		$data = $this->request->data;
		$uuid = $this->request->get('params:uuid');
		$site = $this->site();
		$visitor = $this->checkVisitor();

		$service = new CreateOrUpdateDevice();
		$service->perform([
			'uuid' => $uuid,
			'data' => $data,
			'user' => $visitor,
			'site' => $site,
		]);

		return [ 'success' => true ];
	}
}
