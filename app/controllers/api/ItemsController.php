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

        $items = $this->site->businessItems($type, $conditions, $params);
        $etag = $this->etag($items);
        $self = $this;

        return $this->whenStale($etag, function() use($type, $items, $self) {
            return $self->toJSON(array(
                $type => $items
            ));
        });
    }

    public function show() {
        $bi = \Model::load('BusinessItems')->firstById($this->param('id'));
        $type = \Inflector::camelize($bi->type);
        $bi = \Model::load($type)->firstById($this->param('id'));

        $etag = $this->etag($bi);
        $self = $this;

        return $this->whenStale($etag, function() use($bi, $self) {
            return $self->toJSON(array(
                $bi->type => $bi
            ));
        });
    }

    public function by_category() {
        $categories = \Model::load('Categories')->allBySiteIdAndVisibility($this->site->id, 1);
        $items = array();

        $etag = '';
        foreach($categories as $category) {
            $current_items = $category->childrenItems($this->param('limit', 10));
            $items[$category->id] = $current_items;
            $etag .= $this->etag($current_items);
        }

        $self = $this;

        return $this->whenStale($etag, function() use($items, $self) {
            return $self->toJSON($items);
        });
    }

    public function create() {
        $parent = \Model::load('Categories')->firstById($this->request->data['parent_id']);
        $item = $this->modelInstance($parent, $this->request->data);
        $item->site_id = $this->site->id;

        if($item->validate()) {
            $item->save();
            $this->response->status(201);
            return $this->toJSON(array(
                $item->type => $item
            ));
        }
        else {
            $this->response->status(422);
        }
    }

    public function update() {
        $bi = \Model::load('BusinessItems')->firstById($this->param('id'));
        $item = $this->model($bi->parent())->firstById($this->param('id'));
        $item->updateAttributes($this->request->data);

        if($item->validate()) {
            $item->save();
            $this->response->status(200);
            return $this->toJSON(array(
                $item->type => $item
            ));
        }
        else {
            $this->response->status(422);
        }
    }

    public function destroy() {
        \Model::load('BusinessItems')->delete($this->param('id'));
        $this->response->status(200);
    }

    public function nearest() {
        $parent = \Model::load('Categories')->firstById($this->param('category_id'));
        $items = $this->model($parent)->nearest($this->param('category_id'), $this->param('lat'), $this->param('lng'));

        return $this->toJSON(array(
            'items' => $items
        ));
    }

    protected function modelName($category) {
        return \Inflector::camelize($category->type);
    }

    protected function model($category) {
        return \Model::load($this->modelName($category));
    }

    protected function modelInstance($category, $data) {
        $model = $this->modelName($category);
        \Model::load($model);
        $model = "\\$model";
        $instance = new $model($data);
        $instance->parent_id = $category->id;
        return $instance;
    }
}
