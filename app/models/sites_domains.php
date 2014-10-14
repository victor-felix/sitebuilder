<?php
require_once 'lib/sitemanager/SiteManager.php';

class SitesDomains extends AppModel
{
	protected $beforeDelete = array('deleteFromSiteManager');

	protected $afterSave = array(
		'updateSiteManager'
	);

	protected $validates = array(
		'domain' => array(
			'rule' => 'uniqueDomain',
			'message' => 'This domain is not available'
		),
	);

	protected function uniqueDomain($value) {
		$domain = $this->firstByDomain($value);

		//domain is available
		if (!$domain)
			return true;

		//the domain exists, but in another site
		if ($this->site_id != $domain->site_id)
			return false;

		//the domain exists, is from site, but the site already have the domain
		if ($this->id && $this->id != $domain->id)
			return false;
		
		return true;
	}

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

	public function deleteFromSiteManager($id)
	{
		$self = $this->firstById($id);
		SiteManager::delete($self->domain);
		return $id;
	}

	protected function updateSiteManager($created) {
		$instance = MeuMobi::instance();
		if ($created) {
			SiteManager::create($this->domain, $instance);
		} else {
			SiteManager::update($previous, $domain, $instance);
		}
	}
}
