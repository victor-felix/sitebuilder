<?php

class DashboardController extends AppController
{
	protected $uses = array();

	public function index()
	{
		$site = $this->getCurrentSite();
		$categories = $site->categories();

		if (empty($categories)) {
			$category = null;
		} else {
			$category = $categories[0];
		}

		$this->set(compact('site', 'category'));
	}

	public function analytics_report()
	{
		$this->layout = false;
		$this->analytics->authenticate();
		$this->set(array('analytics' => $this->analytics));
	}

	public function google($data = array())
	{
		if ($this->analytics->authenticate()) {
			Session::writeFlash('success', s('Analytics successfully enabled'));
		} else {
			Session::writeFlash('error', s('Sorry, can\'t enable analytics'));
		}
		$this->redirect('/dashboard/index');
	}

	public function profile()
	{
		if ($this->data) {
			list($profileId, $uiId) = explode(',', $this->data['profile']);
			$this->analytics->profile_id = $profileId;
			$this->analytics->profile_uid = $uiId;
			$this->analytics->save();
			Session::writeFlash('success', s('Profile successfully selected'));
		}
		$this->redirect('/dashboard/index');
	}

	public function disable()
	{
		if ($this->analytics->logout()) {
			Session::writeFlash('success', s('Analytics successfully disabled'));
		} else {
			Session::writeFlash('error', s('Sorry, can\'t disable analytics'));
		}
		$this->redirect('/dashboard/index');
	}
}
