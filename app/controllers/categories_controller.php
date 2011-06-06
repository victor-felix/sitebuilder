<?php

class CategoriesController extends AppController {
    public function index() {
        $categories = $this->getCurrentSite()->categories();
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
                if($this->isXhr()) {
                    $json = array('go_back'=>true,'refresh'=>'/categories');
                    $this->respondToJSON($json);
                }
                else {
                    Session::writeFlash('success', __('Categoria adicionada com sucesso.'));
                    $this->redirect('/categories');
                }
            }
        }

        $this->set(array(
            'category' => $category,
            'parent' => $this->Categories->firstById($parent_id),
            'site' => $this->getCurrentSite()
        ));
    }

    public function edit($id = null) {
        $site = $this->getCurrentSite();
        $category = $this->Categories->firstById($id);
        if(!empty($this->data)) {
            $category->updateAttributes($this->data);
            if($category->validate()) {
                $category->save();
                if($this->isXhr()) {
                    $json = array('go_back'=>true,'refresh'=>'/categories');
                    $this->respondToJSON($json);
                }
                else {
                    Session::writeFlash('success', __('Categoria editada com sucesso.'));
                    $this->redirect('/categories');
                }
            }
        }

        $this->set(array(
            'category' => $category,
            'parent_id' => $category->parent(),
            'site' => $site
        ));
    }

    public function delete($id = null) {
        $this->Categories->delete($id);
        $message = __('Categoria excluída com sucesso.');
        if($this->isXhr()) {
            $json = array('success'=>$message);
            $this->respondToJSON($json);
        }
        else {
            Session::writeFlash('success', $message);
            $this->redirect('/categories');
        }
    }

    public function reorder() {
        $this->autoRender = false;
    }
}
