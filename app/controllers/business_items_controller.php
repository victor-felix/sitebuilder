<?php

class BusinessItemsController extends AppController {
    protected $uses = array('BusinessItems', 'BusinessItemsValues',
        'BusinessItemsTypes', 'Categories');
    
    public function index($parent_id = null) {
        $this->set(array(
            'business_items' => $this->model()->allByParentId($parent_id),
            'category' => $this->Categories->firstById($parent_id)
        ));
    }

    public function add($parent_id = null) {
        $site = $this->getCurrentSite();
        $item = $this->modelInstance($this->data);
        $item->parent_id = $parent_id;

        if(!empty($this->data)) {
            $item->site = $site;

            if($item->validate()) {
                $item->save();

                if($this->isXhr()) {
                    return $this->setAction('index', $item->parent_id);
                }
                else {
                    Session::writeFlash('success', __('Item adicionado com sucesso.'));
                    $this->redirect('/business_items/index/' . $item->parent_id);
                }
            }
        }

        $this->set(array(
            'item' => $item,
            'parent' => $item->parent(),
            'type' => $site->businessItemType()
        ));
    }

    public function edit($id = null) {
        $site = $this->getCurrentSite();
        $item = $this->model()->firstById($id);

        if(!empty($this->data)) {
            $item->updateAttributes($this->data);
            $item->site = $site;

            if($item->validate()) {
                $item->save();

                if($this->isXhr()) {
                    $this->setAction('index', $item->parent_id);
                }
                else {
                    Session::writeFlash('success', __('Item editado com sucesso.'));
                    $this->redirect('/business_items/index/' . $item->parent_id);
                }
            }
        }

        $this->set(array(
            'parent' => $item->parent(),
            'item' => $item,
            'type' => $site->businessItemType()
        ));
    }

    public function delete($id = null) {
        $business_item = $this->BusinessItems->firstById($id);
        $parent_id = $business_item->parent_id;
        $message = __('Item excluído com sucesso.');
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

    protected function modelName() {
        $type = $this->getCurrentSite()->businessItemTypeName();
        return Inflector::camelize($type);
    }

    protected function model() {
        return Model::load($this->modelName());
    }

    protected function modelInstance($data) {
        $model = $this->modelName();
        Model::load($model);
        return new $model($data);
    }
}
