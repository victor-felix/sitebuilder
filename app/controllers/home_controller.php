<?php

class HomeController extends AppController {
    public $uses = array('Feeds', 'Articles');
    
    public function index() {
        $this->Feeds->first()->updateArticles();
        // $this->Articles->deleteAll();
    }
}