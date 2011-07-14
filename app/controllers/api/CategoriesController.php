<?php

namespace app\controllers\api;

class CategoriesController extends \app\controllers\api\ApiController {
    public function index() {
        $categories = \Model::load('Categories')->allBySiteIdAndVisibility($this->site->id, 1);
        $etag = $this->etag($categories);
        $self = $this;

        return $this->whenStale($etag, function() use($categories, $self) {
            return $self->toJSON(array(
                'categories' => $categories
            ));
        });
    }

    public function children($category_id = null) {
        if(!$category_id) {
            $category_id = $this->site->rootCategory()->id;
        }

        $categories = \Model::load('Categories')->recursiveByParentId($category_id, $this->param('depth', 0));
        $etag = $this->etag($categories);
        $self = $this;

        return $this->whenStale($etag, function() use($categories, $self) {
            return $self->toJSON(array(
                'categories' => $categories
            ));
        });
    }

    public function view($category_id = null) {
        $category = \Model::load('Categories')->firstById($category_id);
        $etag = $this->etag($category);
        $self = $this;

        return $this->whenStale($etag, function() use($category, $self) {
            return $self->toJSON(array(
                'categories' => $category
            ));
        });
    }
}
