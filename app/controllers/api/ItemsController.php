<?php

namespace app\controllers\api;

use \app\models\Items;
use \Model;

class ItemsController extends ApiController {
    public function index() {
        $conditions = array(
            'site_id' => $this->site()->id
        );

        if(isset($this->request->query['type'])) {
            $type = $conditions['type'] = $this->request->query['type'];
        }
        else if(isset($this->request->query['category'])) {
            $conditions['parent_id'] = $this->request->query['category'];
            $category_id = $this->request->query['category'];
            $category = Model::load('Categories')->firstById($category_id);
            $type = $category->type;
        }

        $items = Items::find('all', array('conditions' => $conditions));
        $etag = $this->etag($items);
        $self = $this;

        return $this->whenStale($etag, function() use($type, $items, $self) {
            return array($type => $items);
        });
    }

    public function search() {
        $category_id = $this->request->params['category_id'];
        $category = Model::load('Categories')->firstById($category_id);
        $keyword = "/{$this->request->query['keyword']}/u";
        $conditions = array(
            'site_id' => $this->site()->id,
            'or' => array(
                array('title' => array('like' => $keyword)),
                array('description' => array('like' => $keyword))
            )
        );

        $items = Items::find('all', array('conditions' => $conditions));

        return array('items' => $items);
    }

    public function show() {
        $item = Items::find('first', array('conditions' => array(
            '_id' => $this->request->params['id'],
            'site_id' => $this->site()->id
        )));

        $etag = $this->etag($item);
        $self = $this;

        return $this->whenStale($etag, function() use($item, $self) {
            return array($item->type => $item);
        });
    }

    //public function by_category() {
        //$categories = \Model::load('Categories')->allBySiteIdAndVisibility($this->site->id, 1);
        //$items = array();

        //$etag = '';
        //foreach($categories as $category) {
            //$current_items = $category->childrenItems($this->param('limit', 10));
            //$items[$category->id] = $current_items;
            //$etag .= $this->etag($current_items);
        //}

        //$self = $this;

        //return $this->whenStale($etag, function() use($items, $self) {
            //return $self->toJSON($items);
        //});
    //}

    public function create() {
        $item = Items::create($this->request->data);
        $item->site_id = $this->site()->id;

        if($item->save()) {
            $this->response->status(201);
            return array($item->type => $item);
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

        $this->request->data['site_id'] = $this->site()->id;

        if($item->save($this->request->data)) {
            $this->response->status(200);
            return array($item->type => $item);
        }
        else {
            $this->response->status(422);
        }
    }

    public function destroy() {
        Items::remove(array('_id' => $this->request->params['id']));
        $this->response->status(200);
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
