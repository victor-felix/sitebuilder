<?php

class ApiController extends AppController {
    protected $autoRender = false;
    protected $site;

    protected function beforeFilter() {
        $params = $this->param('params');
        $this->site = Model::load('Sites')->firstBySlug($params[0]);
    }
}