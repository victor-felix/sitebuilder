<?php

class Categories extends AppModel {
    protected $beforeDelete = array('deleteChildren');
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
    
    public function toListBySiteId($site_id) {
        return $this->toList(array(
            'conditions' => array(
                'site_id' => $site_id
            )
        ));
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
    
    public function getRoot($site_id) {
        return $this->firstBySiteIdAndParentId($site_id, 0);
    }
    
    public function children($id = null) {
        if(is_null($id)) {
            $id = $this->id;
        }
        
        $categories = Model::load('Categories')->allByParentId($id);
        $bis = Model::load('BusinessItems')->allByParentId($id);
        
        return array_merge($categories, $bis);
    }
    
    public function hasChildren() {
        $conditions = array(
            'conditions' => array(
                'parent_id' => $this->id
            )
        );
        
        $total = 0;
        $total += Model::load('Categories')->count($conditions);
        $total += Model::load('BusinessItems')->count($conditions);
        
        return (bool) $total;
    }
    
    public function childrenCount() {
        return Model::load('BusinessItems')->count(array(
            'conditions' => array(
                'parent_id' => $this->id
            )
        ));
    }
    
    public function toJSON($recursive = true) {
        $data = $this->data;
        
        if($recursive) {
            $data['children'] = array();
            $children = $this->children();
            foreach($children as $child) {
                $data['children'] []= $child->toJSON(false);
            }
        }
        
        return $data;
    }    
    
    public function forceDelete($id) {
        $this->deleteChildren($id, true);
        $this->deleteAll(array(
            'conditions' => array(
                'id' => $id
            )
        ));
    }
    
    protected function deleteChildren($id, $force = false) {
        $self = $this->firstById($id);
        if($self->parent_id == 0 && !$force) {
            return false; // don't allow root's deletion
        }
        
        $children = $self->children();
        foreach($children as $child) {
            $child->delete($child->id);
        }
        
        return $id;
    }
}