<?php

class SitesController extends AppController
{
	protected $protectedActions = array('remove', 'regenerate_domains', 'theme',
		'users');

	protected function beforeFilter()
	{
		$isAdmin = $this->getCurrentSite()->userRole() == Users::ROLE_ADMIN;
		$isProtected = in_array($this->param('action'), $this->protectedActions);
		if (!$isAdmin && $isProtected) {
			Session::writeFlash('error', s('Sorry, you are not allowed to do this'));
			$this->redirect('/categories');
		}
	}

	public function business_info()
	{
		$site = $this->getCurrentSite();

		if (!empty($this->data)) {
			$images = array_unset($this->data, 'image');
			$site->updateAttributes($this->request->data);

			if($site->validate()) {
				$site->save();
				foreach($images as $id => $image) {
					if(is_numeric($id)) {
						$record = Model::load('Images')->firstById($id);
						$record->title = $image['title'];
						$record->save();
					}
				}
				Session::writeFlash('success', s('Configuration successfully saved'));
				$this->redirect('/categories');
			}
		}

		if ($site->state_id) {
			$states = Model::load('States')->toListByCountryId($site->country_id, array(
				'order' => 'name ASC'
			));
		} else {
			$states = array();
		}

		$countries = Model::load('Countries')->toList(array(
			'order' => 'name ASC'
		));

		$this->set(compact('site', 'countries', 'states'));
	}

	public function theme()
	{
		$site = $this->getCurrentSite();

		if (!empty($this->data)) {
			$site->updateAttributes($this->data);
			if ($site->validateTheme()) {
				$site->save();
				Session::writeFlash('success', s('Configuration successfully saved'));
				$this->redirect('/categories');
			}
		}

		$themes = Model::load('Themes')->all();

		$this->set(compact('site', 'themes'));
	}

	public function remove($id = null)
	{
		if ($id) {
			$site = Model::load('Sites')->firstById($id);
		} else {
			$site = $this->getCurrentSite();
		}

		$sucess = false;
		
		//remove site only if has more than one
		if (count(Auth::User()->sites()) > 1) {
			$sucess = $site->delete($site->id);
		}

		if ($sucess) {
			Session::writeFlash('success', s('Site was successfully removed'));
		} else {
			Session::writeFlash('error', s('Sorry, can\'t remove site'));
		}

		$this->redirect('/');
	}

	public function preview($theme = '', $skin = '')
	{
		$this->autoRender = false;
		$site = $this->getCurrentSite();
		$url = 'http://santacasajf.meumobi.com';

		//check if has params, if not use the current site
		if (!$theme || !$skin) {
			$theme = $site->theme;
			$skin = $site->skin;
		}

		$opts = array(
			'http' => array(
				'method' => 'GET',
				'user_agent' => $_SERVER['HTTP_USER_AGENT'],
				'header' => "Accept-language: {$_SERVER['HTTP_ACCEPT_LANGUAGE']}"
			)
		);

		if ($source = file_get_contents($url, false, stream_context_create($opts))) {
			$doc = new DOMDocument();
			$doc->loadHTML($source);
			$headTag = $doc->getElementsByTagName('head')->item(0);
			$baseTag = $doc->createElement("base");
			$baseTag->setAttribute('href', $url);
			$baseTag->setAttribute('target', '_blank');
			$headTag->insertBefore($baseTag, $headTag->firstChild);
			echo $doc->saveHTML();
		}
	}

	public function regenerate_domains()
	{
		$domains = Model::load('SitesDomains')->toList(array('displayField'=>'domain'));
		$sucess = SiteManager::regenerate($domains, MeuMobi::instance());
		if ($sucess) {
			Session::writeFlash('success', s('Domains was successfully regenerated'));
		} else {
			Session::writeFlash('error', s('Sorry, can\'t regenerate domains'));
		}

		$this->redirect('/');
	}

	public function general()
	{
		$site = $this->getCurrentSite();
		if(!empty($this->data)) {
			$site->updateAttributes($this->data);
			if($site->validate() && $site->save()) {
				Session::writeFlash('success', s('Configuration successfully saved'));
			}
		}
		$this->set(array(
			'site' => $site,
		));
	}

	public function custom_domain()
	{
		$this->general();
	}

	public function news()
	{
		$this->general();
	}

	public function verify_slug($slug = null)
	{
		$this->respondToJSON(array(
			'unique' => !$this->Sites->exists(array(
				'slug' => $slug
			))
		));
	}

	public function users()
	{
		$users = $this->getCurrentSite()->users(true);
		$site = $this->getCurrentSite();
		$invites = \app\models\Invites::find('all', array('conditions' => array(
			'site_id' => $site->id,
		)));

		$this->set(compact('users', 'invites'));
	}

	public function remove_user($userId)
	{
		if ($this->getCurrentSite()->removeUser((int) $userId)) {
			Session::writeFlash('success', s('User successfully removed.'));
		} else {
			Session::writeFlash('error', s('Sorry, can\'t remove user.'));
		}
		$this->redirect('/sites/users');
	}
}
