<?php

class Categories extends AppModel {
    protected $beforeDelete = array('promoteChildren');
    protected $defaultScope = array(
        'order' => '`order` ASC'
    );
    
    protected $validates = array(
        'title' => array(
            'rule' => 'notEmpty',
            'message' => 'Você precisa definir um título'
        )
    );

    public function listAvailableParents($site_id) {
        $root = $this->getRoot($site_id);
        $list = array(
            $root->id => $root->title
        );
        
        $list += $this->toList(array(
            'conditions' => array(
                'site_id' => $site_id,
                'parent_id' => $root->id
            )
        ));
        
        return $list;
    }
    
    public function createRoot($site) {
        $root = Model::load('Segments')->firstById($site->segment)->root;
        $this->id = null;
        $this->save(array(
            'title' => __($root),
            'site_id' => $site->id,
            'parent' => 0
        ));
    }
    
    public function children() {
        $categories = Model::load('Categories')->allByParentId($this->id);
        $bis = Model::load('BusinessItems')->allByParentId($this->id);
        return array_merge($categories, $bis);
    }
    
    protected function getRoot($site_id) {
        return $this->firstBySiteIdAndParentId($site_id, 0);
    }
    
    protected function promoteChildren($id) {
        $self = $this->firstById($id);
        if($self->parent_id == 0) {
            return false; // don't allow root's deletion
        }
        
        $children = $self->children();
        foreach($children as $child) {
            $child->delete($child->id);
        }
        
        return $id;
    }
}