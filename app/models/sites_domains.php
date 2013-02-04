<?php

class SitesDomains extends AppModel
{
	protected $validates = array(
		'domain' => array(
			'rule' => array('unique', 'domain'),
			'message' => 'This domain is not available'
		),
	);

	public function check($domain, $siteId = null)
	{
		if ($siteId) {
			return $this->firstByDomainAndSiteId($domain, $siteId);
		} else {
			return $this->firstByDomain($domain);
		}
	}

	public function onDeleteUser($user)
	{
		if ($user->id) {
			$this->deleteAll(array(
				'conditions' => array(
					'user_id' => $user->id
				)
			));
		}
	}

	public function onDeleteSite($site)
	{
		if ($site->id) {
			$this->deleteAll(array(
				'conditions' => array(
					'site_id' => $site->id)
				)
			);
		}
	}
}
