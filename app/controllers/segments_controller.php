<?php

class SegmentsController extends AppController {
    protected $uses = array();
    protected $autoRender = false;
    
    public function index() {
        if($this->param('extension') == 'json') {
            header('Content-type: application/json');
            echo json_encode(Config::read('Segments'));
        }
    }
    
    public function view($segment) {
        dump($this->param('extension'));
        if($this->param('extension') == 'json') {
            // header('Content-type: application/json');

            $segments = Config::read('Segments');
            echo json_encode($segments[$segment]);
        }
    }
}