<?php

use app\models\Jobs;
use meumobi\sitebuilder\repositories\JobEventsRepository;

class StatusController extends AppController
{
	protected $uses = [];
	protected $layout = 'status';

	public function index()
	{
		$apiEndpointStatuses = $this->checkApiEndpoints();

		$jobEvents = new JobEventsRepository();
		$workers = ['UpdateEventsFeedWorker', 'UpdateFeedsWorker'];
		$workerStatuses = $jobEvents->getStatus($workers);

		$oldestJobStatus = $this->checkOldestJob();

		$this->set(compact('workerStatuses', 'oldestJobStatus', 'apiEndpointStatuses'));
	}

	protected function checkApiEndpoints()
	{
		$domain = Config::read('Sites.domain');
		$sites = Config::read('Status.sites');
		$statuses = [];
		$site_to_handle = [];
		$multi = curl_multi_init();

		foreach ($sites as $site) {
			$url = "http://$domain/api/$site";

			$handle = curl_init();
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

			curl_multi_add_handle($multi, $handle);

			$site_to_handle[$site] = $handle;
		}

		$running = null;

		do { curl_multi_exec($multi, $running); } while ($running);

		foreach ($site_to_handle as $site => $handle) {
			curl_multi_remove_handle($multi, $handle);
			$statuses []= [
				'site' => $site,
				'status' => curl_getinfo($handle, CURLINFO_HTTP_CODE),
				'content_type' => curl_getinfo($handle, CURLINFO_CONTENT_TYPE),
			];
		}

		curl_multi_close($multi);

		return $statuses;
	}

	protected function checkOldestJob()
	{
		$oneHourAgo = time() - 3600;
		$oldestJob = Jobs::first([ 'order' => ['modified' => 'ASC'] ]);

		return [
			'worker' => 'WorkerManager',
			'ok' => $oldestJob->created->sec > $oneHourAgo
		];
	}
}
