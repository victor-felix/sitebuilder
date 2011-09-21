<?php

namespace app\controllers\api;

require_once 'app/models/categories.php';

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

    public function show() {
        $category = \Model::load('Categories')->firstById($this->param('id'));
        $etag = $this->etag($category);
        $self = $this;

        return $this->whenStale($etag, function() use($category, $self) {
            return $self->toJSON(array(
                'categories' => $category
            ));
        });
    }

    public function children() {
        $category_id = $this->param('id');

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

    public function create() {
        $category = new \Categories($this->request->data);
        $category->site_id = $this->site->id;

        if($category->validate()) {
            $category->save();
            $this->response->status(201);
            return $this->toJSON(array(
                'categories' => $category
            ));
        }
        else {
            $this->response->status(422);
        }
    }

    public function update() {
        $category = \Model::load('Categories')->firstById($this->param('id'));
        $category->updateAttributes($this->request->data);

        if($category->validate()) {
            $category->save();
            $this->response->status(200);
            return $this->toJSON(array(
                'categories' => $category
            ));
        }
        else {
            $this->response->status(422);
        }
    }

    public function destroy() {
        \Model::load('Categories')->delete($this->param('id'));
        $this->response->status(200);
    }
}
