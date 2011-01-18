<?php

require 'app/controllers/api/api_controller.php';

class HomeController extends ApiController {
    protected $uses = array('Sites');
    
    public function api_index($domain) {
        $this->respondToJSON(
            $this->Sites->firstByDomain($domain)
        );
    }
}