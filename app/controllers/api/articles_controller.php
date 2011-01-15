<?php

require 'app/controllers/api/api_controller.php';

class ArticlesController extends ApiController {
    public function api_index($slug = null) {
        $this->respondToJSON($this->Articles->allBySiteSlug($slug));
    }
    
    public function api_view($id = null) {
        $this->respondToJSON($this->Articles->firstById($id));
    }
}