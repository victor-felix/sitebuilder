<?php

namespace app\models;

use Config;
use Inflector;

use lithium\util\Collection;
Collection::formats('lithium\net\http\Media');

class Items extends \lithium\data\Model {
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
        'created'  => array('type' => 'date', 'default' => 0),
        'modified'  => array('type' => 'date', 'default' => 0),
        'type'  => array('type' => 'string', 'null' => false),
        'title'  => array('type' => 'string', 'null' => false)
    );

    public function id($entity) {
        return $entity->_id->{'$id'};
    }

    public function imageModel() {
        return 'Items';
    }

    public function resizes() {
        $config = Config::read($this->imageModel() . '.resizes');
        if(is_null($config)) {
            $config = array();
        }

        return $config;
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
