<?php

require_once 'lib/bbcode/Decoda.php';

class BusinessItems extends AppModel {
    protected $table = 'business_items';
    protected $beforeSave = array('setSiteValues', 'getOrder');
    protected $afterSave = array('saveItemValues', 'saveImages');
    protected $beforeDelete = array('deleteValues', 'deleteImages');

    protected $fields = array();
    protected $scope = array();

    public function __construct($data = null) {
        parent::__construct($data);

        if(!is_null($this->id)) {
            $this->data = array_merge($this->data, (array) $this->values());
        }
    }

    public function typeName() {
        return $this->typeName;
    }

    public function breadcrumbs($category_id) {
        return Model::load('Categories')->firstById($category_id)->bredcrumbs();
    }

    public function allBySlug($slug) {
        $site = Model::load('Sites')->firstBySlug($slug);
        return $this->allBySiteId($site->id);
    }

    public function allOrdered($params) {
        $params += $this->scope;
        $conditions = $params['conditions'];
        $conditions['type'] = Inflector::underscore(get_class($this));

        if(array_key_exists('order', $params)) {
            list($field, $sort) = explode(' ', $params['order']);
            $conditions['v.field'] = $field;
            $order = sprintf('v.value %s', $sort);
        }
        else {
            $order = '`order` ASC';
        }

        return $this->all(array(
            'table' => array('i' => $this->table()),
            'fields' => 'DISTINCT i.*',
            'joins' => 'JOIN business_items_values AS v ' .
                'ON i.id = v.item_id',
            'order' => $order,
            'conditions' => $conditions
        ));
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

        if($values->format == 'bbcode') {
            $parser = new Decoda($values->description);
            $bbcode = $parser->parse(true);
            $values->description = '<p>'. $bbcode . '</p>';
        }

        $fields = array('id', 'site_id', 'parent_id', 'type', 'order', 'created', 'modified');
        foreach($fields as $field) {
            $values->{$field} = $this->data[$field];
        }

        $images = $this->images();
        $values->images = array();

        foreach($images as $image) {
            $values->images []= $image->toJSON();
        }

        return $values;
    }

    public function validate($data = array()) {
        return true; // temporary, mind you
    }

    public function description() {
        $field = $this->field('description');

        if(!is_null($field)) {
            if($field->type == 'richtext') {
                $parser = new Decoda($this->description);
                $bbcode = $parser->parse(true);
                return strip_tags($bbcode);
            }
            else {
                return $this->description;
            }
        }
        else {
            return '';
        }
    }

    protected function setSiteValues($data) {
        if(is_null($this->id)) {
            if(array_key_exists('site', $data)) {
                $data['site_id'] = $this->site->id;
            }

            $data['type'] = Inflector::underscore(get_class($this));
        }

        return $data;
    }

    protected function saveItemValues($created) {
        $model = Model::load('BusinessItemsValues');

        if(!$created) {
            $values = $model->toListByItemId($this->id);
        }

        foreach($this->fields as $id => $field) {
            if($created) {
                $model->id = null;
            }
            else {
                $model->id = $values[$id];
            }

            if(array_key_exists($id, $this->data)) {
                $value = $this->data[$id];
            }
            elseif(array_key_exists('default', $field)) {
                $value = $field['default'];
            }
            else {
                $value = '';
            }

            $model->updateAttributes(array(
                'item_id' => $this->id,
                'field' => $id,
                'value' => $value
            ));
            $model->save();
        }
    }

    protected function getOrder($data) {
        if(is_null($this->id) && array_key_exists('parent_id', $data)) {
            $siblings = $this->toList(array(
                'fields' => array('id', '`order`'),
                'conditions' => array(
                    'site_id' => $data['site_id'],
                    'parent_id' => $data['parent_id']
                ),
                'order' => '`order` DESC',
                'displayField' => 'order',
                'limit' => 1
            ));

            if(!empty($siblings)) {
                $data['order'] = current($siblings) + 1;
            }
            else {
                $data['order'] = 0;
            }
        }

        return $data;
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

    public function fields() {
        return array_keys($this->fields);
    }

    public function field($field) {
        if(array_key_exists($field, $this->fields)) {
            return (object) $this->fields[$field];
        }
        else {
            return null;
        }
    }

    public function imageModel() {
        return 'BusinessItems';
    }
}
