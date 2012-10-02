<?php

namespace app\models\items;

use app\models\Items;

class Stores extends \app\models\Items {
    protected $type = 'Store';

    protected $fields = array(
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'address' => array(
            'title' => 'Address',
            'type' => 'string'
        ),
        'phone' => array(
            'title' => 'Phone',
            'type' => 'string'
        ),
        'web' => array(
            'title' => 'Web',
            'type' => 'string'
        ),
        'mail' => array(
            'title' => 'Mail',
            'type' => 'string'
        ),
        'hours' => array(
            'title' => 'Hours',
            'type' => 'string'
        ),
        'related' => array(
            'title' => 'Related',
            'type' => array('related', 'Products')
        ),
        'featured' => array(
            'title' => 'Featured?',
            'type' => 'boolean'
        )
    );

    public static function __init() {
        parent::__init();

        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + array(
            'related'  => array('type' => 'array', 'default' => array()),
            'geo'  => array('type' => 'array', 'default' => 0),
            'address'  => array('type' => 'string', 'default' => ''),
            'phone'  => array('type' => 'string', 'default' => ''),
            'web'  => array('type' => 'string', 'default' => ''),
            'mail'  => array('type' => 'string', 'default' => ''),
            'hours'  => array('type' => 'string', 'default' => ''),
            'featured'  => array('type' => 'boolean', 'default' => false),
        );
    }
}

Stores::applyFilter('save', function($self, $params, $chain) {
    return Items::addTimestamps($self, $params, $chain);
});

Stores::applyFilter('save', function($self, $params, $chain) {
    return Items::addGeocode($self, $params, $chain);
});

Stores::finder('nearest', function($self, $params, $chain) {
    return Items::nearestFinder($self, $params, $chain);
});

Stores::finder('within', function($self, $params, $chain) {
    return Items::withinFinder($self, $params, $chain);
});

Stores::applyFilter('save', function($self, $params, $chain) {
	return Items::addOrder($self, $params, $chain);
});
