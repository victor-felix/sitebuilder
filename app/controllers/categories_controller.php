<?php

class CategoriesController extends AppController {
    public function index() {
        $categories = $this->Categories->allBySiteId($this->getCurrentSite()->id);
        $tree = array();
        foreach($categories as $category) {
            $tree[$category->parent_id] []= $category;
        }
        
        $this->set(array(
            'categories' => $tree,
            'root' => $this->getCurrentSite()->rootCategory()
        ));
    }
    
    public function add() {
        $site = $this->getCurrentSite();
        
        if(!empty($this->data)) {
            $this->data['site_id'] = $site->id;
            if($this->Categories->validate($this->data)) {
                $this->Categories->save($this->data);
                $this->redirect('/categories');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'parents' => $this->Categories->listAvailableParents($site->id)
        ));
    }
    
    public function edit($id = null) {
        $site = $this->getCurrentSite();

        if(!empty($this->data)) {
            $this->Categories->id = $id;
            if($this->Categories->validate($this->data)) {
                $this->Categories->save($this->data);
                $this->redirect('/categories');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'category' => $this->Categories->firstById($id),
            'parents' => $this->Categories->listAvailableParents($site->id)
        ));
    }
    
    public function delete($id = null) {
        $this->Categories->delete($id);
        $this->redirect('/categories');
    }    
}