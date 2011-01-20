<?php

class SitesController extends AppController {
    public function register() {
        $this->editRecord('/sites/customize_register');
    }

    public function edit() {
        $this->editRecord('/sites/edit');
    }
    
    public function customize_edit() {
        $this->customizeSite('/sites/customize_edit');
    }

    public function customize_register() {
        $this->customizeSite('/sites/finished');
    }
    
    public function finished() {
        $this->set(array(
            'site' => $this->getCurrentSite()
        ));
    }
    
    protected function editRecord($redirect_to) {
        $site = $this->getCurrentSite();
        if(!empty($this->data)) {
            $site->updateAttributes($this->data);
            if($site->validate()) {
                $site->save();
                $this->redirect($redirect_to);
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'site' => $site
        ));
    }

    protected function customizeSite($redirect_to) {
        $site = $this->getCurrentSite();
        if(!empty($this->data)) {
            $site->updateAttributes($this->data);
            if($site->validate()) {
                $site->save();
                $this->redirect($redirect_to);
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
}