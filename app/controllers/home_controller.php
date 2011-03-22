<?php

class HomeController extends AppController {
    public $uses = array();
    public $layout = 'home';
    
    public function index() {
        if(Auth::loggedIn()) {
            $this->redirect('/categories');
        }
    }
}