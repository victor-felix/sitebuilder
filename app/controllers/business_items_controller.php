<?php

class BusinessItemsController extends AppController {
    protected $uses = array('BusinessItems', 'BusinessItemsValues', 'Categories');
    
    public function index($parent_id = null) {
        $category = $this->Categories->firstById($parent_id);
        $business_items = $this->model($category)->allByParentId($category->id);
        $this->set(compact('category', 'business_items'));
    }

    public function add($parent_id = null) {
        $site = $this->getCurrentSite();
        $parent = Model::load('Categories')->firstById($parent_id);
        $item = $this->modelInstance($parent, $this->data);

        if(!empty($this->data)) {
            $item->site = $site;

            if($item->validate()) {
                $item->save();

                if($this->isXhr()) {
                    return $this->setAction('index', $item->parent_id);
                }
                else {
                    Session::writeFlash('success', s('Item successfully added.'));
                    $this->redirect('/business_items/index/' . $item->parent_id);
                }
            }
        }

        $this->set(array(
            'item' => $item,
            'parent' => $parent
        ));
    }

    public function edit($id = null) {
        $site = $this->getCurrentSite();
        $bi = $this->BusinessItems->firstById($id);
        $item = $this->model($bi->parent())->firstById($id);

        if(!empty($this->data)) {
            $item->updateAttributes($this->data);
            $item->site = $site;

            if($item->validate()) {
                $item->save();

                if($this->isXhr()) {
                    $this->setAction('index', $item->parent_id);
                }
                else {
                    Session::writeFlash('success', s('Item successfully updated.'));
                    $this->redirect('/business_items/index/' . $item->parent_id);
                }
            }
        }

        $this->set(array(
            'parent' => $item->parent(),
            'item' => $item
        ));
    }

    public function delete($id = null) {
        $business_item = $this->BusinessItems->firstById($id);
        $parent_id = $business_item->parent_id;
        $message = s('Item successfully deleted.');
        $this->BusinessItems->delete($id);
        if($this->isXhr()) {
            $json = array(
                'success'=>$message,
                'go_back'=>true,
                'refresh'=>'/business_items/index/' . $parent_id);
            $this->respondToJSON($json);
            // $this->setAction('index', $business_item->parent_id);
        }
        else {
            Session::writeFlash('success', $message);
            $this->redirect('/business_items/index/' . $business_item->parent_id);
        }
    }
    
    public function reorder() {
        $this->autoRender = false;
    }

    protected function modelName($category) {
        return Inflector::camelize($category->type);
    }

    protected function model($category) {
        return Model::load($this->modelName($category));
    }

    protected function modelInstance($category, $data) {
        $model = $this->modelName($category);
        Model::load($model);
        $instance = new $model($data);
        $instance->parent_id = $category->id;
        return $instance;
    }
}
