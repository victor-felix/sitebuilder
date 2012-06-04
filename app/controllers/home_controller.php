<?php

class HomeController extends AppController {
    public $uses = array('users');
    public $layout = 'home';

    public function index() {
        if(Auth::loggedIn()) {
            if(Auth::user()->site()->hide_categories) {
                $this->redirect('/settings');
            }
            else {
                $this->redirect('/categories');
            }
        }
        
        if(!Users::signupIsEnabled()) {
        	$this->redirect('/login');
        }
    }
}
