<?php

class BusinessItems extends AppModel {
    protected $beforeSave = array('setSiteValues');
    protected $afterSave = array('saveItemValues');
    protected $beforeDelete = array('deleteValues');

    public function values() {
        $obj = array();
        $values = Model::load('BusinessItemsValues')->allByItemId($this->id);
        
        foreach($values as $value) {
            $obj[$value->field] = $value->value;
        }
        
        return (object) $obj;
    }
    
    protected function setSiteValues($data) {
        if(is_null($this->id) && array_key_exists('site', $data)) {
            $data['site_id'] = $this->site->id;
            $data['type'] = $this->site->businessItemTypeName();
            $data['is_new'] = true;
        }
        
        return $data;
    }
    
    protected function saveItemValues() {
        $fields = $this->site->businessItemType()->fields;
        $model = Model::load('BusinessItemsValues');
        $is_new = array_key_exists('is_new', $this->data);
        
        if(!$is_new) {
            $values = $model->toListByItemId($this->id);
        }

        foreach($fields as $id => $field) {
            if($is_new) {
                $model->id = null;
            }
            else {
                $model->id = $values[$id];
            }
            $data = array(
                'item_id' => $this->id,
                'field' => $id,
                'value' => $this->data[$id]
            );
            $model->save($data);
        }
    }
    
    protected function deleteValues($id) {
        Model::load('BusinessItemsValues')->deleteAll(array(
            'conditions' => array(
                'item_id' => $id
            )
        ));
        
        return $id;
    }
}