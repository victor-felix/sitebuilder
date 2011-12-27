<?php

class SitesController extends AppController {
    public function register() {
        $this->editRecord('/sites/customize_register');
    }
    
    public function add() {
    	$site = Model::load('Sites');
    
    	if(!empty($this->data)) {
    		$site->segment = MeuMobi::segment();
    		$site->updateAttributes($this->request->data);
    		if($site->validate() && $site->save()) {
    			Session::writeFlash('success', s('Configuration successfully saved.'));
    			Session::write('Users.registering', '/sites/customize_register');
    			$this->redirect('/sites/customize_register');
    			return;
    		}
    	}
    
    	$this->set(array(
    			'site' => $site,
    			'countries' => Model::load('Countries')->toList(array(
    					'order' => 'name ASC'
    			)),
    			'states' => array(),
    	));
    
    }

    public function edit() {
        $this->editRecord('/sites/edit');
    }
    
    public function customize_edit() {
        $this->customizeSite(s('Configuration successfully saved.'), '/sites/customize_edit');
    }

    public function customize_register() {
        $this->customizeSite(s('Configuration successfully saved.'), '/sites/finished');
    }
    
    public function finished() {
        $this->set(array(
            'site' => $this->getCurrentSite()
        ));
    }
    
    public function verify_slug($slug = null) {
        $this->respondToJSON(array(
            'unique' => !$this->Sites->exists(array(
                'slug' => $slug
            ))
        ));
    }
    
    protected function editRecord($redirect_to) {
        $site = $this->getCurrentSite();
        if(!empty($this->data)) {
            $site->updateAttributes($this->request->data);
            if($site->validate()) {
                $site->save();
                Session::writeFlash('success', s('Configuration successfully saved.'));
                if($redirect_to == '/sites/customize_register') {
                    Session::write('Users.registering', '/sites/customize_register');
                }
                $this->redirect($redirect_to);
            }
        }

        if($site->state_id) {
            $states = Model::load('States')->toListByCountryId($site->country_id, array(
                'order' => 'name ASC'
            ));
        }
        else {
            $states = array();
        }

        $this->set(array(
            'site' => $site,
            'countries' => Model::load('Countries')->toList(array(
                'order' => 'name ASC'
            )),
            'states' => $states
        ));
    }

    protected function customizeSite($message, $redirect_to) {
        $site = $this->getCurrentSite();
        if(!empty($this->data)) {
            $site->updateAttributes($this->data);
            if($site->validate()) {
                $site->save();
                Session::writeFlash('success', $message);
                if($redirect_to == '/sites/finished') {
                    Session::delete('Users.registering');
                }
                $this->redirect($redirect_to);
            }
        }

        $this->set(array(
            'site' => $site,
            'themes' => Model::load('Themes')->all()
        ));
    }
}
