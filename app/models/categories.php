<?php

class Categories extends AppModel {
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
    
    protected function getRoot($site_id) {
        return $this->firstBySiteIdAndParentId($site_id, 0);
    }
}