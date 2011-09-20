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
}
