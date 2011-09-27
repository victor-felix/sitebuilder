<?php

class HomeController extends AppController {
    public $uses = array();
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
    }
}
