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
	
	public function index()
	{
		$language = $this->param('locale') ? $this->param('locale') : $this->detectBrowserLanguage();
		$this->set(compact('language'));
	}
}
