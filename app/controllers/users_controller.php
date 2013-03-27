<?php

class UsersController extends AppController
{
	protected $allowedActions = array('reset_password', 'forgot_password',
		'login', 'confirm');

	protected function beforeFilter()
	{
		parent::beforeFilter();

		$allowed = in_array($this->param('action'), $this->allowedActions);
		if (!$allowed) $this->redirectIfUnauthenticated();
	}

	public function edit()
	{
		$user = Auth::user();
		if (!empty($this->data)) {
			$user->updateAttributes($this->data);
			if ($user->validate()) {
				$user->save();
				Session::writeFlash('success', s('Configuration successfully saved'));
				$this->redirect('/users/edit');
			}
		}
		$this->set(compact('user'));
	}

	public function confirm($id = null, $token = null)
	{
		$user = $this->Users->firstById($id);
		if ($user->confirm($token)) {
			if (!Auth::loggedIn()) {
				Auth::login($user);
			}
			Session::writeFlash('success', s('Account successfully created'));
			$this->redirect('/dashboard');
		}
	}

	public function login()
	{
		if (!empty($this->data)) {
			$user = Auth::identify($this->data);
			if ($user) {
				Auth::login($user, (bool) $this->data['remember']);
				if (!$user->hasSiteInSegment(MeuMobi::segment())) {
					$this->redirect('/create_site/theme');
				}
				if ($location = Session::read('Auth.redirect')) {
					Session::delete('Auth.redirect');
				} else {
					$location = '/dashboard';
				}
				$this->redirect($location);
			} else {
				Session::writeFlash('error', s('Invalid username or password'));
			}
		}
	}

	public function logout()
	{
		Auth::logout();
		$this->redirect('/');
	}

	public function forgot_password()
	{
		$user = new Users();
		if (!empty($this->data)) {
			if ($user->requestForNewPassword($this->data['email'])) {
				Session::writeFlash('success', s('Forgot password mail send successfully'));
			}
		}
		$this->set(array('user' => $user));
	}

	public function reset_password($user_id = null, $token = null)
	{
		if ($user_id) {
			$user = $this->Users->firstById($user_id);

			if ($user->token != $token) {
				$this->redirect('/');
			}
		} else {
			$this->redirect ('/');
		}

		if (!empty($this->data)) {
			$user->updateAttributes($this->data);
			if ($user->resetPassword()) {
				Session::writeFlash('success', s('Password successfully reseted'));
				$this->redirect('/login');
			}
		}
		$this->set(array('user' => $user));
	}

	public function change_site($id = null)
	{
		Auth::user()->site($id);
		$this->redirect('/dashboard');
	}

	public function invite()
	{
		if (Users::ROLE_ADMIN != $this->getCurrentSite()->role ||
			!MeuMobi::currentSegment()->enableMultiUsers()) $this->redirect('/');

		if (isset($this->data['emails'])) {
			Auth::user()->invite($this->data['emails']);

			$message = s('Users invited successfully');
			if ($this->isXhr()) {
				$this->respondToJSON(array(
					'success' => $message,
					'go_back' => true,
					'refresh' => '/sites/users'
				));
			} else {
				Session::writeFlash('success', $message);
				$this->redirect('/sites/users');
			}
		}
	}
}
