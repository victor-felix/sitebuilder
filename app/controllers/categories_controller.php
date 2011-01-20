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
    
    public function add($parent_id = null) {
        $site = $this->getCurrentSite();
        $category = new Categories($this->data);
        if(!empty($this->data)) {
            $category->site_id = $site->id;
            if($category->validate()) {
                $category->save();
                $this->redirect('/categories');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'category' => $category,
            'parent_id' => $parent_id
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
