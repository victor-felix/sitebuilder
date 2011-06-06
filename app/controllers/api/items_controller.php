<?php

require 'app/controllers/api/api_controller.php';

class ItemsController extends ApiController {
    protected $uses = array('BusinessItems');
    
    public function api_index($slug = null) {
        $conditions = array();
        $params = array();

        $category = $this->param('category');
        if(!is_null($category)) {
            $conditions['parent_id'] = $category;
            $category = Model::load('Categories')->firstById($category);
            $type = $category->type;
        }
        else {
            $type = $this->param('type');
        }

        $klass = Inflector::camelize($type);

        $this->respondToJSON(array(
            $type => $this->site->businessItems($type, $conditions, $params)
        ));
    }

    public function api_view($slug = null, $id = null) {
        $bi = $this->BusinessItems->firstById($id);
        $this->respondToJSON(array(
            $bi->type => $bi
        ));
    }

    public function api_by_category($slug = null) {
        $categories = Model::load('Categories')->allBySiteIdAndVisibility($this->site->id, 1);
        $items = array();

        foreach($categories as $category) {
            $items[$category->id] = $category->childrenItems($this->param('limit', 10));
        }

        $this->respondToJSON($items);
    }
}
