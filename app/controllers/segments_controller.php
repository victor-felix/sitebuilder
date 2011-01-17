<?php

class SegmentsController extends AppController {
    protected $uses = array();
    protected $autoRender = false;
    
    public function view($segment) {
        header('Content-type: application/json');
        $segments = Config::read('Segments');
        echo json_encode($segments[$segment]);
    }
}