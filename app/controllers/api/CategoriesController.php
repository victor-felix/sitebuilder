<?php

namespace app\controllers\api;

class CategoriesController extends \app\controllers\api\ApiController {
    public function index() {
        return $this->toJSON(array(
            'categories' => \Model::load('Categories')->allBySiteIdAndVisibility($this->site->id, 1)
        ));
    }

    public function children($category_id = null) {
        if(!$category_id) {
            $category_id = $this->site->rootCategory()->id;
        }

        return $this->toJSON(array(
            'categories' => \Model::load('Categories')->recursiveByParentId($category_id, $this->param('depth', 0))
        ));
    }

    public function view($category_id = null) {
        return $this->toJSON(array(
            'categories' => \Model::load('Categories')->firstById($category_id)
        ));
    }
}
