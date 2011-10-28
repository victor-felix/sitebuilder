<?php

namespace app\models\items;

class Articles extends \app\models\Items {
    protected $type = 'Link';

    protected $fields = array(
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'link' => array(
            'title' => 'Link',
            'type' => 'string'
        )
    );

    public static function __init() {
        parent::__init();

        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + array(
            'link'  => array('type' => 'string', 'default' => '')
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
