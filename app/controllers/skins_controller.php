<?php

class SkinsController extends AppController {
    protected $uses = array('Themes');

    public function index() {
        $this->set(array(
            'skins' => $this->Themes->firstByName($this->param('theme'))->colors
        ));
    }
}
