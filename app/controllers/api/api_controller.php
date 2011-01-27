<?php

class ApiController extends AppController {
    protected $autoRender = false;
    protected $domain;
    
    protected function beforeFilter() {
        $params = $this->param('params');
        $this->site = Model::load('Sites')->firstByDomain($params[0]);
    }
    
    protected function respondToJSON($record) {
        header('Content-type: application/json');
        echo json_encode($this->toJSON($record));
    }
}