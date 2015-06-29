<?php

namespace app\models\items;

use app\models\Items;

class Links extends \app\models\Items {
    protected $type = 'Link';

    protected $fields = array(
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'link' => array(
            'title' => 'Link',
            'type' => 'string'
        ),
        'group' => array(
            'title' => 'Group',
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

Links::applyFilter('save', function($self, $params, $chain) {
    return Items::addTimestamps($self, $params, $chain);
});
