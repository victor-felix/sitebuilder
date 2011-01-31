<?php

require 'app/controllers/api/api_controller.php';

class ItemsController extends ApiController {
    protected $uses = array('BusinessItems');
    
    public function api_index($slug = null) {
        // TODO implement type
        $conditions = array();
        
        if(!is_null($category = $this->param('category'))) {
            $conditions['parent_id'] = $category;
        }

        $type = $this->site->businessItemTypeName();
        $this->respondToJSON(array(
            $type => $this->site->businessItems($conditions)
        ));
    }

    public function api_view($slug = null, $id = null) {
        // TODO implement type
        $bi = $this->BusinessItems->firstById($id);
        $this->respondToJSON(array(
            $bi->type => $bi
        ));
    }
}