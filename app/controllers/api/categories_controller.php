<?php

require 'app/controllers/api/api_controller.php';

class CategoriesController extends ApiController {
    public function api_view($domain, $id = null) {
        $this->respondToJSON(
            $this->Categories->firstById($id)
        );
    }    
}