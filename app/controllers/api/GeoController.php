<?php

namespace app\controllers\api;

class GeoController extends \app\controllers\api\ApiController {
    public function nearest() {
        $parent = \Model::load('Categories')->firstById($this->param('category_id'));
        $items = $this->model($parent)->nearest($this->param('category_id'), $this->param('lat'), $this->param('lng'));

        $type = $parent->type;
        $etag = $this->etag($items);
        $self = $this;

        return $this->whenStale($etag, function() use($type, $items, $self) {
            return $self->toJSON(array(
                $type => $items
            ));
        });
    }

    public function inside() {
        $parent = \Model::load('Categories')->firstById($this->param('category_id'));
        $category_id = $parent->id;
        $ne_lat = $this->param('ne_lat');
        $ne_lng = $this->param('ne_lng');
        $sw_lat = $this->param('sw_lat');
        $sw_lng = $this->param('sw_lng');
        $items = $this->model($parent)->area($category_id, $ne_lat, $ne_lng, $sw_lat, $sw_lng);

        $type = $parent->type;
        $etag = $this->etag($items);
        $self = $this;

        return $this->whenStale($etag, function() use($type, $items, $self) {
            return $self->toJSON(array(
                $type => $items
            ));
        });
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
