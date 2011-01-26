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
                Session::writeFlash('success', __('Categoria adicionada com sucesso.'));
                $this->redirect('/categories');
            }
        }

        $this->set(array(
            'category' => $category,
            'parent' => $this->Categories->firstById($parent_id)
        ));
    }
    
    public function edit($id = null) {
        $category = $this->Categories->firstById($id);
        if(!empty($this->data)) {
            $category->updateAttributes($this->data);
            if($category->validate()) {
                $category->save();
                if($this->isXhr()) {
                    $this->renderJSON($category);
                }
                else {
                    Session::writeFlash('success', __('Categoria editada com sucesso.'));
                    $this->redirect('/categories');
                }
            }
        }
        
        $this->set(array(
            'category' => $category,
            'parent_id' => $category->parent_id
        ));
    }
    
    public function delete($id = null) {
        $this->Categories->delete($id);
        Session::writeFlash('success', __('Categoria excluída com sucesso.'));
        if($this->isXhr()) {
            $this->autoRender = false;
        }
        else {
            $this->redirect('/categories');
        }
    }    

    public function reorder() {
        $this->autoRender = false;
    }
}