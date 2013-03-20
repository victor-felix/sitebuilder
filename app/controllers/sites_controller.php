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
			$this->redirect('/dashboard');
		}

		parent::beforeFilter();
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
				$this->redirect('/dashboard');
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
				$this->redirect('/dashboard');
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

	//TODO  Need to create a script, not a action, to perform this
	public function regenerate_domains($previusDomainToBeReplaced = false, $segment = false)
	{
		$segment = $segment ? $segment : MeuMobi::segment();
		$sites = Model::load('Sites')->toList(array(
				'conditions' => array('segment' => $segment),
				));

		$sitesIds = array_keys($sites);

		//replace previus domains
		//very descriptive var
		if ($previusDomainToBeReplaced) {
			$domains = Model::load('SitesDomains')->all(array(
					'conditions' => array(
							'site_id' => $sitesIds,
							'domain LIKE' =>  "%$previusDomainToBeReplaced",
					),
			));

			foreach ($domains as $item) {
			$item->domain = str_replace($previusDomainToBeReplaced, MeuMobi::domain(), $item->domain);
			$item->save();
			}
		}

		$domains = Model::load('SitesDomains')->toList(array(
			'displayField' => 'domain',
			'conditions' => array('site_id' => $sitesIds),
		));

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
		if (!empty($this->data)) {
			$site->updateAttributes($this->data);
			if ($site->validate() && $site->save()) {
				Session::writeFlash('success', s('Configuration successfully saved'));
			}
		}
		$this->set(compact('site'));
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
		if (!MeuMobi::currentSegment()->enableMultiUsers()) {
			$this->redirect('/');
		}

		$users = $this->getCurrentSite()->users(true);
		$site = $this->getCurrentSite();
		$invites = \app\models\Invites::find('all', array('conditions' => array(
			'site_id' => $site->id,
		)));

		$this->set(compact('users', 'invites'));
	}

	public function remove_user($user_id)
	{
		if (!MeuMobi::currentSegment()->enableMultiUsers()) {
			$this->redirect('/');
		}

		if ($this->getCurrentSite()->removeUser($user_id)) {
			Session::writeFlash('success', s('User successfully removed.'));
		} else {
			Session::writeFlash('error', s('Sorry, can\'t remove user.'));
		}
		$this->redirect('/sites/users');
	}
}
