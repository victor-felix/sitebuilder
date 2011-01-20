<?php

class ThemesController extends AppController {
    protected $uses = array('Segments');
    protected $autoRender = false;
    
    public function skins($theme) {
        header('Content-type: application/json');
        $segment = Config::read('Segments.default');
        $skins = Model::load('Segments')->firstById($segment)->skins;
        echo json_encode($skins[$theme]);
    }
}