<?php

use app\models\Jobs;
use meumobi\sitebuilder\repositories\JobEventsRepository;

class StatusController extends AppController
{
	protected $uses = [];
	protected $layout = 'login';

	public function index()
	{
		// TODO check if API responds

		// $this->checkApiEndpoints();

		$jobEvents = new JobEventsRepository();
		$workers = ['UpdateEventsFeedWorker', 'UpdateFeedsWorker'];
		$workerStatuses = $jobEvents->getStatus($workers);

		$oldestJob = Jobs::first([ 'order' => ['modified' => 'ASC'] ]);

		$this->set(compact('workerStatuses', 'oldestJob'));
	}

	protected function checkApiEndpoints()
	{
		// http://php.net/manual/en/function.curl-multi-init.php
		$urls = ['http://google.com'];
		$url_to_handle = [];
		$multi = curl_multi_init();

		foreach ($urls as $url) {
			$handle = curl_init();
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

			curl_multi_add_handle($multi, $handle);

			$url_to_handle[$url] = $handle;
		}

		$running = null;

		do { curl_multi_exec($multi, $running); } while ($running);

		foreach ($url_to_handle as $url => $handle) {
			curl_multi_remove_handle($multi, $handle);
			pr(curl_multi_getcontent($handle));
			pr(curl_getinfo($handle, CURLINFO_HTTP_CODE));
		}

		curl_multi_close($multi);
	}
}
