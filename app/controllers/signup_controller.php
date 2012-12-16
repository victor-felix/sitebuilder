<?php

require_once 'app/models/sites.php';
require_once 'app/models/users.php';

class SignupController extends AppController
{
	protected $uses = array();
	protected $layout = 'register';

	protected function beforeFilter()
	{
		if (Auth::loggedIn()) {
			$this->redirect('/categories');
		}

		if (!MeuMobi::currentSegment()->isSignupEnabled()) {
			$this->redirect('/users/login');
		}

		if ($signup = Session::read('Signup')) {
			if ($signup['path'] != $this->param('here')) {
				$this->redirect($signup['path']);
			}
		}
	}

	public function user()
	{
		$user = new Users();

		if (!empty($this->data)) {
			$user->updateAttributes($this->data);
			if ($user->validate()) {
				Session::write('Signup', array(
					'path' => '/signup/theme',
					'user' => $user->data
				));
				$this->redirect('/signup/theme');
			}
		}

		$this->set(compact('user'));
	}

	public function theme()
	{
		$session = Session::read('Signup');

		$site = new Sites(array('segment' => MeuMobi::segment()));

		if (!empty($this->data)) {
			$site->updateAttributes($this->data);
			if ($site->validateTheme()) {
				Session::write('Signup', array(
					'path' => '/signup/business_info',
					'user' => $session['user'],
					'site' => $site->data
				));
				$this->redirect('/signup/business_info');
			}
		}

		$themes = Model::load('Themes')->all();

		$this->set(compact('site', 'themes'));
	}

	public function business_info()
	{
		$session = Session::read('Signup');

		$user = new Users($session['user']);
		$site = new Sites($session['site']);

		if (!empty($this->data)) {
			$site->updateAttributes($this->data);

			if ($site->validate()) {
				$user->save();
				$site->save();
				Session::delete('Signup');
				Session::writeFlash('success', s('Congratulations! Your mobile site is ready!'));
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
}
