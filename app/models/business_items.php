<?php

require_once 'app/models/business_items_types.php';

class BusinessItems extends AppModel {
    protected $beforeSave = array('setSiteValues');
    protected $afterSave = array('saveItemValues', 'saveImages');
    protected $beforeDelete = array('deleteValues', 'deleteImages');
    protected $defaultScope = array(
        'order' => '`order` ASC'
    );

    public function __construct($data = null) {
        parent::__construct($data);
        
        if(!is_null($this->id)) {
            $this->data = array_merge($this->data, (array) $this->values());
        }
    }

    public function breadcrumbs($category_id) {
        return Model::load('Categories')->firstById($category_id)->bredcrumbs();
    }

    public function allBySlug($slug) {
        $site = Model::load('Sites')->firstBySlug($slug);
        return $this->allBySiteId($site->id);
    }

    public function values() {
        $obj = array();
        $values = Model::load('BusinessItemsValues')->allByItemId($this->id);
        
        foreach($values as $value) {
            $obj[$value->field] = $value->value;
        }
        
        return (object) $obj;
    }

    public function parent() {
        return Model::load('Categories')->firstById($this->parent_id);
    }

    public function typesForParent($parent_id) {
        return $this->all(array(
            'fields' => 'DISTINCT type',
            'conditions' => array(
                'parent_id' => $parent_id
            )
        ));
    }

    public function images() {
        return Model::load('Images')->allByRecord('BusinessItems', $this->id);
    }

    public function image() {
        return Model::load('Images')->firstByRecord('BusinessItems', $this->id);
    }

    public function toJSON() {
        $values = $this->values();
        
        $fields = array('id', 'site_id', 'parent_id', 'type', 'order', 'created', 'modified');
        foreach($fields as $field) {
            $values->{$field} = $this->data[$field];
        }
        
        return $values;
    }

    public function validate($data = array()) {
        $fields = $this->site->businessItemType()->fields;

        foreach($fields as $id => $field) {
            if(array_key_exists($id, $this->data)) {
                list($valid, $message) = BusinessItemsTypes::validate($field, $this->data[$id]);
                if(!$valid) {
                    $this->errors[$id] = $message;
                }
            }
        }

        return empty($this->errors);
    }

    protected function setSiteValues($data) {
        if(is_null($this->id) && array_key_exists('site', $data)) {
            $data['site_id'] = $this->site->id;
            $data['type'] = $this->site->businessItemTypeName();
        }
        
        return $data;
    }
    
    protected function saveItemValues($created) {
        $fields = $this->site->businessItemType()->fields;
        $model = Model::load('BusinessItemsValues');
        
        if(!$created) {
            $values = $model->toListByItemId($this->id);
        }

        foreach($fields as $id => $field) {
            if($created) {
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

    protected function saveImages() {
        if(array_key_exists('image', $this->data) && $this->data['image']['error'] == 0) {
            if($image = $this->image()) {
                Model::load('Images')->delete($image->id);
            }
            Model::load('Images')->upload($this, $this->data['image']);
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