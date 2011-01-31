<?php

require 'app/controllers/api/api_controller.php';

class ItemsController extends ApiController {
    protected $uses = array('BusinessItems', 'Articles');
    
    public function api_index($slug = null) {
        if($this->param('type', 'products') == 'products') {
            $conditions = array();

            if(!is_null($category = $this->param('category'))) {
                $conditions['parent_id'] = $category;
            }

            $type = $this->site->businessItemTypeName();
            $this->respondToJSON(array(
                $type => $this->site->businessItems($conditions)
            ));
        }
        else {
            $this->respondToJSON(array(
                'articles' => $this->site->topArticles()
            ));
        }
    }

    public function api_view($slug = null, $id = null) {
        if($this->param('type', 'products') == 'products') {
            $bi = $this->BusinessItems->firstById($id);
            $this->respondToJSON(array(
                $bi->type => $bi
            ));
        }
        else {
            $this->respondToJSON(array(
                'articles' => $this->Articles->firstById($id)
            ));
        }
    }
}