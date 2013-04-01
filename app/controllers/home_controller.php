<?php

class HomeController extends AppController
{
	protected $uses = array();
	protected $layout = 'home';

	protected function beforeFilter()
	{
		parent::beforeFilter();

		if (Auth::loggedIn()) {
			$this->redirect('/dashboard');
		}

		if (!MeuMobi::currentSegment()->isSignupEnabled()) {
			$this->redirect('/users/login');
		}
	}
}