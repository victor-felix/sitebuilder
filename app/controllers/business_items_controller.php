<?php

class BusinessItemsController extends AppController {
    protected $uses = array('BusinessItems', 'BusinessItemsValues', 'BusinessItemsTypes');
    
    public function index() {
        $this->set('business_items', $this->BusinessItems->allBySiteId($this->getCurrentSite()->id));
    }
    
    public function add() {
        $site = $this->getCurrentSite();
        
        if(!empty($this->data)) {
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
            'type' => $site->businessItemType(),
            'categories' => Model::load('Categories')->toListBySiteId($site->id)
        ));
    }
    
    public function edit($id = null) {
        $site = $this->getCurrentSite();

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
            'business_item' => $this->BusinessItems->firstById($id),
            'type' => $site->businessItemType(),
            'categories' => Model::load('Categories')->toListBySiteId($site->id)
        ));
    }
    
    public function delete($id = null) {
        $this->BusinessItems->delete($id);
        $this->redirect('/business_items');
    }
}