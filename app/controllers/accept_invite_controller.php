<?php

require_once 'app/models/users.php';

class AcceptInviteController extends AppController
{
	protected $uses = array();

	public function login($token)
	{
		if (!Users::validateInvite($token)) {
			$this->redirect('/');
		}

		if (!empty($this->data)) {
			$user = Auth::identify($this->data);
			if ($user) {
				Auth::login($user, (bool) $this->data['remember']);
				if ($user->confirmInvite($token)) {
					Session::writeFlash('success', s('Congratulations, your invitation was confirmed'));
					$this->redirect('/categories');
				} else {
					Session::writeFlash('error', s('Sorry, your invitation can\'t de confirmed'));
					Auth::logout();
				}
			} else {
				Session::writeFlash('error', s('Invalid username or password'));
			}
		}

		$this->set(compact('token'));
	}

	public function signup($token)
	{
		if (!Users::validateInvite($token)) {
			$this->redirect('/');
		}

		$user = new Users();

		if (!empty($this->data)) {
			$user->updateAttributes($this->data);
			if ($user->validate()) {
				$user->save();
				$user->confirmInvite($token);
				Session::writeFlash('success', s('Congratulations, your invitation was confirmed'));
				$this->redirect('/categories');
			}
		}

		$this->set(compact('user'));
		$this->layout = 'register';
		echo $this->render('signup/user');
	}
}
