<?php

class DomainsController extends AppController
{
	protected $uses = array();
	protected $layout = 'default';

	public function delete($id)
	{
		Model::load('SitesDomains')->delete($id);
		$this->getCurrentSite()->save();
		$message = s('Domain successfully deleted.');
		if ($this->isXhr()) {
			$this->respondToJSON(array(
				'success' => $message,
				'refresh' => '/sites/custom_domain'
			));
		} else {
			Session::writeFlash('success', $message);
			$this->redirect('/sites/custom_domain');
		}
	}
}