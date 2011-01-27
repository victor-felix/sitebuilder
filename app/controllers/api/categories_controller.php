<?php

require 'app/controllers/api/api_controller.php';

class CategoriesController extends ApiController {
    public function api_index($domain) {
        $this->respondToJSON(array(
            'categories' => $this->site->rootCategory()
        ));
    }
    
    public function api_view($domain, $id = null) {
        $this->respondToJSON(array(
            'categories' => $this->Categories->firstById($id)
        ));
    }    
}