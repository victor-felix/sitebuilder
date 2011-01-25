<?php

require 'app/controllers/api/api_controller.php';

class HomeController extends ApiController {
    protected $uses = array('Sites');
    
    public function api_index($domain) {
        $site = $this->Sites->firstByDomain($domain);
        $this->respondToJSON(array(
            'siteInfo' => $site->toJSON()
        ));
    }
}