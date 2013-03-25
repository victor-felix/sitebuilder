<?php

require_once 'app/models/sites.php';
require_once 'app/models/users.php';

class SignupController extends AppController
{
	protected $uses = array();
	protected $layout = 'register';
	protected $workflowSteps = array('user', 'theme', 'business_info');

	protected function beforeFilter()
	{
		if (Auth::loggedIn()) {
			$this->redirect('/dashboard');
		}

		if (!MeuMobi::currentSegment()->isSignupEnabled()) {
			$this->redirect('/users/login');
		}

		if ($session = Session::read('Signup')) {
			$currentStep = array_search($session['step'], $this->workflowSteps);
			$attemptedStep = array_search($this->param('action'), $this->workflowSteps);
			if ($currentStep < $attemptedStep) {
				$this->redirect("/signup/{$session['step']}");
			}
		}
	}

	public function user()
	{
		$session = Session::read('Signup');

		$user = new Users();

		if ($session && array_key_exists('user', $session)) {
			$user->updateAttributes($session['user']);
		}

		if (!empty($this->data)) {
			$user->updateAttributes($this->data);
			if ($user->validate()) {
				Session::write('Signup', array(
					'step' => 'theme',
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

		if (array_key_exists('site', $session)) {
			$site->updateAttributes($session['site']);
		}

		if (!empty($this->data)) {
			$site->updateAttributes($this->data);
			if ($site->validateTheme()) {
				Session::write('Signup', array(
					'step' => 'business_info',
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
				$site->addDefaultPhotos();
				Session::delete('Signup');
				Session::writeFlash('success', s('Congratulations! Your mobile site is ready!'));
				$this->redirect('/dashboard');
			}
		}
		$this->set(compact('site'));
	}
}
