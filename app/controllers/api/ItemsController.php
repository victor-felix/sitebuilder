<?php

namespace app\controllers\api;

class ItemsController extends \app\controllers\api\ApiController {
    public function index() {
        $conditions = array();
        $params = array();

        $category = $this->param('category');
        if($category) {
            $conditions['parent_id'] = $category;
            $category = \Model::load('Categories')->firstById($category);
            $type = $category->type;
        }
        else {
            $type = $this->param('type');
        }

        return $this->toJSON(array(
            $type => $this->site->businessItems($type, $conditions, $params)
        ));
    }

    public function view($item_id = null) {
        $bi = \Model::load('BusinessItems')->firstById($item_id);
        $type = \Inflector::camelize($bi->type);
        $bi = \Model::load($type)->firstById($item_id);

        return $this->toJSON(array(
            $bi->type => $bi
        ));
    }

    public function by_category() {
        $categories = \Model::load('Categories')->allBySiteIdAndVisibility($this->site->id, 1);
        $items = array();

        foreach($categories as $category) {
            $items[$category->id] = $category->childrenItems($this->param('limit', 10));
        }

        return $this->toJSON($items);
    }
}
