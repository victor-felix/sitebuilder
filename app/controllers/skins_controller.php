<?php

class SkinsController extends AppController {
    protected $uses = array('Themes');

    public function index() {
        $this->set(array(
            'skins' => $this->Themes->firstById($this->param('theme'))->colors
        ));
    }
}
