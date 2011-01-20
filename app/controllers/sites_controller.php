<?php

class SitesController extends AppController {
    public function register($id = null) {
        $site = $this->Sites->firstById($id);
        if(!empty($this->data)) {
            $site->updateAttributes($this->data);
            if($site->validate()) {
                $site->save();
                $this->redirect('/sites/customize/' . $site->id);
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'site' => $site,
            'is_new' => true,
        ));
    }

    public function edit($id = null) {
        $site = $this->Sites->firstById($id);
        if(!empty($this->data)) {
            $site->updateAttributes($this->data);
            if($site->validate()) {
                $site->save();
                $this->redirect('/sites/edit/' . $site->id);
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'site' => $site,
            'is_new' => true,
        ));
    }
    
    public function customize($id = null) {
        $site = $this->Sites->firstById($id);
        if(!empty($this->data)) {
            $site->updateAttributes($this->data);
            if($site->validate()) {
                $site->save($this->data);
                $this->redirect('/sites/finished/' . $site->id);
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'site' => $site,
            'themes' => Model::load('Segments')->firstById($site->segment)->themes,
            'skins' => Model::load('Segments')->firstById($site->segment)->skins
        ));
    }
    
    public function finished($id = null) {
        $this->set(array(
            'site' => $this->Sites->firstById($id)
        ));
    }
}