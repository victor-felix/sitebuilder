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
            if($business_item->validate()) {
                $business_item->save();
                $this->redirect('/business_items/index/' . $business_item->parent_id);
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'business_item' => $business_item,
            'parent_id' => $parent_id,
            'type' => $site->businessItemType()
        ));
    }
    
    public function edit($id = null) {
        $site = $this->getCurrentSite();
        $business_item = $this->BusinessItems->firstById($id);
        if(!empty($this->data)) {
            $this->BusinessItems->id = $id;
            $this->data['site'] = $site;
            if($this->BusinessItems->validate($this->data)) {
                $this->BusinessItems->save($this->data);
                $this->redirect('/business_items');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'parent_id' => $business_item->parent_id,
            'business_item' => $this->BusinessItems->firstById($id),
            'type' => $site->businessItemType(),
            'categories' => Model::load('Categories')->toListBySiteId($site->id)
        ));
    }
    
    public function delete($id = null) {
        $business_item = $this->BusinessItems->firstById($id);
        $this->BusinessItems->delete($id);
        $this->redirect('/business_items/index/' . $business_item->parent_id);
    }
}