<?php

namespace app\models\items;

class Articles extends \app\models\Items {
    public static function __init() {
        parent::__init();

        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + array(
            'guid'  => array('type' => 'string', 'default' => ''),
            'link'  => array('type' => 'string', 'default' => ''),
            'pubdate'  => array('type' => 'date', 'default' => 0),
            'format'  => array('type' => 'string', 'default' => 'bbcode'),
            'description'  => array('type' => 'string', 'default' => ''),
            'author'  => array('type' => 'string', 'default' => ''),
        );
    }
}

Articles::applyFilter('save', function($self, $params, $chain) {
    $item = $params['entity'];

    if(!$item->id) {
        $item->created = date('Y-m-d H:i:s');
    }

    $item->modified = date('Y-m-d H:i:s');

    return $chain->next($self, $params, $chain);
});
