<?php

namespace app\models;

use Config;
use Inflector;
use Model;

use lithium\util\Collection;
Collection::formats('lithium\net\http\Media');

class Items extends \lithium\data\Model {
    protected $getters = array();
    protected $setters = array();
    protected $_meta = array(
        'name' => null,
        'title' => null,
        'class' => null,
        'source' => 'items',
        'connection' => 'default',
        'initialized' => false,
        'key' => '_id',
        'locked' => false
    );

    protected $_schema = array(
        '_id'  => array('type' => 'id'),
        'site_id' => array('type' => 'integer', 'null' => false),
        'parent_id' => array('type' => 'integer', 'null' => false),
        'order' => array('type' => 'integer', 'default' => 0),
        'created'  => array('type' => 'date', 'default' => 0),
        'modified'  => array('type' => 'date', 'default' => 0),
        'type'  => array('type' => 'string', 'null' => false),
        'title'  => array('type' => 'string', 'null' => false)
    );

    public function breadcrumbs($entity, $category_id) {
        return Model::load('Categories')->firstById($category_id)->bredcrumbs();
    }

    public function images($entity) {
        return Model::load('Images')->allByRecord('Items', $this->id($entity));
    }

    public function image($entity) {
        return Model::load('Images')->firstByRecord('Items', $this->id($entity));
    }

    public function id($entity) {
        if($entity->_id) {
            return $entity->_id->{'$id'};
        }
    }

    public function imageModel() {
        return 'Items';
    }

    public function parent($entity) {
        return Model::load('Categories')->firstById($entity->parent_id);
    }

    public function resizes() {
        $config = Config::read('BusinessItems.resizes');
        if(is_null($config)) {
            $config = array();
        }

        return $config;
    }

    public function fields($entity) {
        return array('title');
    }

    public function field($entity, $field) {
        if(array_key_exists($field, $this->_schema)) {
            return (object) $this->_schema[$field];
        }
        else {
            return null;
        }
    }

    public function type($entity) {
        return $this->type;
    }

    public function hasAttribute($entity, $attr) {
        return !is_null($entity->{$attr});
    }

    public function hasGetter($entity, $attr) {
        return in_array($attr, $this->getters);
    }

    public function hasSetter($entity, $attr) {
        return in_array($attr, $this->setters);
    }
}

Items::applyFilter('save', function($self, $params, $chain) {
    $item = $params['entity'];

    if(!$item->id) {
        $item->created = date('Y-m-d H:i:s');
    }

    $item->modified = date('Y-m-d H:i:s');

    return $chain->next($self, $params, $chain);
});

Items::finder('type', function($self, $params, $chain) {
    $result = $chain->next($self, $params, $chain)->rewind();
    $classname = '\app\models\items\\' . Inflector::camelize($result->type);

    return $classname::find('first', $params['options']);
});
