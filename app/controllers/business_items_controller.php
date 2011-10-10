<?php

use app\models\Items;

class BusinessItemsController extends AppController {
    protected $uses = array('Categories');

    public function index($parent_id = null) {
        $category = $this->Categories->firstById($parent_id);

        $classname = '\app\models\items\\' . Inflector::camelize($category->type);
        $business_items = $classname::find('all', array('conditions' => array(
            'parent_id' => $category->id
        )));

        $this->set(compact('category', 'business_items'));
    }

    public function add($parent_id = null) {
        $site = $this->getCurrentSite();
        $parent = Model::load('Categories')->firstById($parent_id);
        $classname = '\app\models\items\\' . Inflector::camelize($parent->type);
        $item = $classname::create();
        $item->type = $parent->type;

        if(!empty($this->data)) {
            $images = array_unset($this->data, 'image');
            $item->set($this->data);
            $item->parent_id = $parent->id;
            $item->site_id = $site->id;
            $item->type = $parent->type;

            if($item->save()) {
                foreach($images as $image) {
                    Model::load('Images')->upload($item, $image);
                }
                if($this->isXhr()) {
                    return $this->setAction('index', $item->parent_id);
                }
                else {
                    Session::writeFlash('success', s('Item successfully added.'));
                    $this->redirect('/business_items/index/' . $item->parent_id);
                }
            }
        }

        $this->set(array(
            'item' => $item,
            'parent' => $parent
        ));
    }

    public function edit($id = null) {
        $site = $this->getCurrentSite();
        $item = Items::find('type', array('conditions' => array(
            '_id' => $id 
        )));

        if(!empty($this->data)) {
            $images = array_unset($this->data, 'image');
            $item->set($this->data);
            $item->site_id = $site->id;

            if($item->save()) {
                if($this->isXhr()) {
                    $this->setAction('index', $item->parent_id);
                }
                else {
                    Session::writeFlash('success', s('Item successfully updated.'));
                    $this->redirect('/business_items/index/' . $item->parent_id);
                }
            }
        }

        $this->set(array(
            'parent' => $item->parent(),
            'item' => $item
        ));
    }

    public function delete($id = null) {
        $item = Items::find('first', array('conditions' => array(
            '_id' => $id 
        )));
        $parent_id = $item->parent_id;
        Items::remove(array('_id' => $id));
        $message = s('Item successfully deleted.');

        if($this->isXhr()) {
            $json = array(
                'success'=>$message,
                'go_back'=>true,
                'refresh'=>'/business_items/index/' . $parent_id
            );
            $this->respondToJSON($json);
        }
        else {
            Session::writeFlash('success', $message);
            $this->redirect('/business_items/index/' . $item->parent_id);
        }
    }

    public function reorder() {
        $this->autoRender = false;
    }
}
