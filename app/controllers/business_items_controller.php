<?php

class BusinessItemsController extends AppController {
    protected $uses = array('BusinessItems', 'BusinessItemsValues', 'BusinessItemsTypes', 'Categories');
    
    public function index($parent_id = null) {
        $this->set(array(
            'business_items' => $this->BusinessItems->allByParentId($parent_id),
            'category' => $this->Categories->firstById($parent_id)
        ));
    }
    
    public function add($parent_id = null) {
        $site = $this->getCurrentSite();
        $business_item = new BusinessItems($this->data);
        if(!empty($this->data)) {
            $business_item->site = $site;
            $parent_id = $business_item->parent_id;
            if($business_item->validate()) {
                $business_item->save();
                if($this->isXhr()) {
                    $this->setAction('index', $business_item->parent_id);
                    return;
                }
                else {
                    Session::writeFlash('success', __('Item adicionado com sucesso.'));
                    $this->redirect('/business_items/index/' . $business_item->parent_id);
                }
            }
        }
        $this->set(array(
            'business_item' => $business_item,
            'parent' => $this->Categories->firstById($parent_id),
            'type' => $site->businessItemType()
        ));
    }
    
    public function edit($id = null) {
        $site = $this->getCurrentSite();
        $business_item = $this->BusinessItems->firstById($id);
        if(!empty($this->data)) {
            $business_item->updateAttributes($this->data);
            $business_item->site = $site;
            if($business_item->validate()) {
                $business_item->save();
                if($this->isXhr()) {
                    $this->setAction('index', $business_item->parent_id);
                }
                else {
                    Session::writeFlash('success', __('Item editado com sucesso.'));
                    $this->redirect('/business_items/index/' . $business_item->parent_id);
                }
            }
        }
        $this->set(array(
            'parent' => $business_item->parent(),
            'business_item' => $business_item,
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
            $this->renderJSON($json);
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
}