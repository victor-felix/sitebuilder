<?php

require 'app/controllers/api/api_controller.php';

class ItemsController extends ApiController {
    protected $uses = array('BusinessItems', 'Articles');
    
    public function api_index($slug = null) {
        $type = $this->param('type');
        $conditions = array(
            'type' => $type
        );

        if(!is_null($category = $this->param('category'))) {
            $conditions['parent_id'] = $category;
        }

        $this->respondToJSON(array(
            $type => $this->site->businessItems($conditions)
        ));
    }

    public function api_view($slug = null, $id = null) {
        $bi = $this->BusinessItems->firstById($id);
        $this->respondToJSON(array(
            $bi->type => $bi
        ));
    }
}
