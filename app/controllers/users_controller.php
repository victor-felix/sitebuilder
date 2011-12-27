<?php

class UsersController extends AppController {
	protected $redirectIf = array ('register', 'login', 'forgot_password', 'reset_password', 'login_and_register' );
	
	protected function beforeFilter() {
		if (Auth::loggedIn ()) {
			if (in_array ( $this->param ( 'action' ), $this->redirectIf )) {
				if (Auth::user ()->site ()->hide_categories) {
					$this->redirect ( '/sites/edit' );
				} else {
					$this->redirect ( '/categories' );
				}
			}
		}
		
		parent::beforeFilter ();
	}
	
	public function edit() {
		$user = $this->Users->firstById ( Auth::user ()->id );
		$this->saveUser ( $user, '/users/edit' );
	}
	
	public function register() {
		$user = new Users ();
		$this->saveUser ( $user, '/sites/register' );
	}
	
	public function confirm($id = null, $token = null) {
		$user = $this->Users->firstById ( $id );
		if ($user->confirm ( $token )) {
			if (! Auth::loggedIn ()) {
				Auth::login ( $user );
			}
			Session::writeFlash ( 'success', s ( 'Account successfully created' ) );
			if (Auth::user ()->site ()->hide_categories) {
				$this->redirect ( '/sites/edit' );
			} else {
				$this->redirect ( '/categories' );
			}
		}
	}
	
	public function login() {
		if (! empty ( $this->data )) {
			$user = Auth::identify ( $this->data );
			if ($user) {
				Auth::login ( $user, ( bool ) $this->data ['remember'] );
				if (! $user->hasSiteInSegment ( MeuMobi::segment () )) {
					$user->registerNewSite ();
					$this->redirect ( '/sites/register' );
					Session::write ( 'Users.registering', '/sites/register' );
				}
				if (! ($location = Session::flash ( 'Auth.redirect' ))) {
					if (Auth::user ()->site ()->hide_categories) {
						$location = '/settings';
					} else {
						$location = '/categories';
					}
				}
				$this->redirect ( $location );
			} else {
				Session::writeFlash ( 'error', s ( 'Invalid username or password' ) );
			}
		}
	}
	
	public function login_and_register() {
		if (! empty ( $this->data )) {
			$user = Auth::identify ( $this->data );
			if ($user) {
				if (! $user->hasSiteInSegment ( MeuMobi::segment () )) {
					$user->registerNewSite ();
					$this->redirect ( '/sites/register' );
					Session::write ( 'Users.registering', '/sites/register' );
				} else {
					$this->setAction ( 'login' );
				}
			} else {
				Session::writeFlash ( 'error', s ( 'Invalid username or password' ) );
			}
		}
		
		echo $this->render ( 'users/login' );
	}
	
	public function logout() {
		Auth::logout ();
		$this->redirect ( '/' );
	}
	
	public function forgot_password() {
		$user = new Users ();
		if (! empty ( $this->data )) {
			if ($user->requestForNewPassword ( $this->data ['email'] )) {
				Session::writeFlash ( 'success', s ( 'Forgot password mail send successfully' ) );
			}
		}
		$this->set ( array ('user' => $user ) );
	}
	
	public function reset_password($user_id = null, $token = null) {
		if ($user_id) {
			$user = $this->Users->firstById ( $user_id );
			
			if ($user->token != $token) {
				$this->redirect ( '/' );
			}
		} else {
			$this->redirect ( '/' );
		}
		
		if (! empty ( $this->data )) {
			$user->updateAttributes ( $this->data );
			if ($user->resetPassword ()) {
				Session::writeFlash ( 'success', s ( 'Password successfully reseted' ) );
				$this->redirect ( '/login' );
			}
		}
		$this->set ( array ('user' => $user ) );
	}
	
	public function change_site($id = null) {
		Auth::user ()->site ( $id );
		$this->redirect ( '/' );
	}
	
	protected function saveUser($user, $redirect) {
		if (! empty ( $this->data )) {
			$user->updateAttributes ( $this->data );
			if ($user->validate ()) {
				$user->save ();
				Session::writeFlash ( 'success', s ( 'Configuration successfully saved' ) );
				Session::write ( 'Users.registering', '/sites/register' );
				$this->redirect ( $redirect );
			}
		}
		$this->set ( array ('user' => $user ) );
	}
}
