<?php

class HomeController extends AppController
{
	protected $uses = array();
	protected $layout = 'home';

	protected function beforeFilter()
	{
		if (Auth::loggedIn()) {
			$this->redirect('/categories');
		}

		if (!MeuMobi::currentSegment()->isSignupEnabled()) {
			$this->redirect('/users/login');
		}
	}
}
