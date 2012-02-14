<?php

namespace app\controllers\api;

use app\models\Items;
use Model;
use Inflector;

class ExportController extends ApiController {
    public function export() {
        $conditions = array(
            'site_id' => $this->site()->id
        );
        $category_id = $this->request->params['category_id'];
        $category = Model::load('Categories')->firstById($category_id);
        $conditions['parent_id'] = $category->id;
        $type = $conditions['type'] = $category->type;
        $classname = '\app\models\items\\' . Inflector::camelize($type);
        $items = $classname::find('all', array('conditions' => $conditions));

        set_time_limit(0);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=$category_id.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $classname::exportTo('csv', $conditions);
        exit;
    }

    protected function checkToken() {}

    protected function toCSV($items) {
        $results = array();

        foreach($items as $item) {
            $fields = array_filter($item->fields(), function($field) {
                return !is_array($field['type']);
            });
            $result = array(
                'id' => $item->id(),
                'type' => $item->type
            );
            foreach($fields as $field) {
                $result[$field] = $item[$field];
            }
            $results []= $result;
        }

        return $results;
    }

    public function related() {
        $item = Items::find('first', array('conditions' => array(
            '_id' => $this->request->params['id'],
            'site_id' => $this->site()->id
        )));

        $classname = '\app\models\items\\' . Inflector::camelize($item->type);
        $related = $classname::find('all', array('conditions' => array(
            '_id' => $item->related->to('array'),
            'site_id' => $this->site()->id
        )));

        $etag = $this->etag($related);
        $self = $this;

        return $this->whenStale($etag, function() use($related, $self) {
            return $self->toJSON($related);
        });
    }

    public function search() {
        $category_id = $this->request->params['category_id'];
        $category = Model::load('Categories')->firstById($category_id);
        $keyword = "/{$this->request->query['keyword']}/iu";
        $conditions = array(
            'site_id' => $this->site()->id,
            'parent_id' => $category->id,
            'title' => array('like' => $keyword)
        );

        $classname = '\app\models\items\\' . Inflector::camelize($category->type);
        $items = $classname::find('all', array('conditions' => $conditions));

        return $this->toJSON($items);
    }

    public function show() {
        $item = Items::find('type', array('conditions' => array(
            '_id' => $this->request->params['id'],
            'site_id' => $this->site()->id
        )));

        $etag = $this->etag($item);
        $self = $this;

        return $this->whenStale($etag, function() use($item, $self) {
            return $item->toJSON();
        });
    }

    public function by_category() {
        $categories = Model::load('Categories')->allBySiteIdAndVisibility($this->site()->id, 1);
        $items = array();

        $etag = '';
        foreach($categories as $category) {
            $current_items = $category->childrenItems($this->param('limit', 10));
            $items[$category->id] = $current_items->to('array');
            $etag .= $this->etag($current_items);
        }

        $self = $this;

        return $this->whenStale($etag, function() use($items, $self) {
            return $items;
        });
    }

    public function create() {
        $category_id = $this->request->data['parent_id'];
        $category = Model::load('Categories')->firstById($category_id);
        $classname = '\app\models\items\\' . Inflector::camelize($category->type);
        $item = $classname::create();
        $item->set($this->request->data);
        $item->site_id = $this->site()->id;
        $item->type = $category->type;

        if($item->save()) {
            $this->response->status(201);
            return $item->toJSON();
        }
        else {
            $this->response->status(422);
        }
    }

    public function update() {
        $item = Items::find('first', array('conditions' => array(
            '_id' => $this->request->params['id'],
            'site_id' => $this->site()->id
        )));

        $item->set(array(
            'site_id' => $this->site()->id
        ) + $this->request->data);

        if($item->save()) {
            $this->response->status(200);
            return $item->toJSON();
        }
        else {
            $this->response->status(422);
        }
    }

    public function destroy() {
        Items::remove(array('_id' => $this->request->params['id']));
        $this->response->status(200);
    }
}

