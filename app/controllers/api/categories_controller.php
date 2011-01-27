<?php

require 'app/controllers/api/api_controller.php';

class CategoriesController extends ApiController {
    public function api_index($domain = null, $parent_id = null) {
        if(is_null($parent_id)) {
            $parent_id = $this->site->rootCategory()->id;
        }
        
        $this->respondToJSON(array(
            'categories' => $this->Categories->recursiveByParentId($parent_id, $this->param('depth', 1))
        ));
    }
    
    public function api_view($domain = null, $id = null) {
        $this->respondToJSON(array(
            'categories' => $this->Categories->firstById($id)
        ));
    }
}