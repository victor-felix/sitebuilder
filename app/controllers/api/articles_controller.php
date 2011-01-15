<?php

require 'app/controllers/api/api_controller.php';

class ArticlesController extends ApiController {
    public function api_index($domain = null) {
        $this->respondToJSON(
            $this->Articles->allByDomain($domain)
        );
    }
    
    public function api_view($domain, $id = null) {
        $this->respondToJSON(
            $this->Articles->firstById($id)
        );
    }
}