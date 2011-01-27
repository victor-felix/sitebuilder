<?php

require 'app/controllers/api/api_controller.php';

class CategoriesController extends ApiController {
    public function api_index($domain = null, $id = null) {
        if(is_null($id)) {
            $category = $this->site->rootCategory();
        }
        else {
            $category = $this->Categories->firstById($id);
        }
        
        $this->respondToJSON(array(
            'categories' => $category->toJSON($this->param('depth', 1))
        ));
    }
    
    public function api_view($domain = null, $id = null) {
        $this->respondToJSON(array(
            'categories' => $this->Categories->firstById($id)
        ));
    }
}