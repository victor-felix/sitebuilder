<?php

require 'app/controllers/api/api_controller.php';

class SitesController extends ApiController {
    public function api_index() {
        $this->respondToJSON(array(
            'sites' => $this->Sites->all()
        ));
    }
    
    public function api_view($slug = null) {
        $site = $this->Sites->firstBySlug($slug);
        $this->respondToJSON(array(
            'sites' => $site->toJSON()
        ));
    }
}