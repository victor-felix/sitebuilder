<?php

class SitesController extends AppController {
    public function index() {
        $this->set('sites', $this->Sites->all());
    }
    
    public function add() {
        if(!empty($this->data)) {
            if($this->Sites->validate($this->data)) {
                $this->Sites->save($this->data);
                $this->redirect('/sites');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'segments' => Model::load('Segments')->toList()
        ));
    }
    
    public function edit($id = null) {
        if(!empty($this->data)) {
            $this->Sites->id = $id;
            if($this->Sites->validate($this->data)) {
                $this->Sites->save($this->data);
                $this->redirect('/sites');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $site = $this->Sites->firstById($id);
        $themes = Model::load('Segments')->firstById($site->segment)->themes;
        $this->set(array(
            'site' => $site,
            'themes' => $themes
        ));
    }
    
    public function delete($id = null) {
        $this->Sites->delete($id);
        $this->redirect('/sites');
    }
}