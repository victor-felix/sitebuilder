<?php

class Categories extends AppModel {
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
    
    protected function getRoot($site_id) {
        return $this->firstBySiteIdAndParentId($site_id, 0);
    }
}