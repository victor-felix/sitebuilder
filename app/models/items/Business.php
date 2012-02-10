<?php

namespace app\models\items;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

use app\models\Items;

class Business extends \app\models\Items {
    protected $type = 'Business';

    protected $fields = array(
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Description',
            'type' => 'richtext'
        ),
        'address' => array(
            'title' => 'Address',
            'type' => 'string'
        ),
        'phone' => array(
            'title' => 'Phone',
            'type' => 'string'
        ),
        'activity' => array(
            'title' => 'Activity',
            'type' => 'string'
        ),
        'web' => array(
            'title' => 'Web',
            'type' => 'string'
        ),
        'mail' => array(
            'title' => 'Mail',
            'type' => 'string'
        )
    );

    public static function __init() {
        parent::__init();

        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + array(
            'geo'  => array('type' => 'array', 'default' => 0),
            'description'  => array('type' => 'string', 'default' => ''),
            'address'  => array('type' => 'string', 'default' => ''),
            'phone'  => array('type' => 'string', 'default' => ''),
            'activity'  => array('type' => 'string', 'default' => ''),
            'web'  => array('type' => 'string', 'default' => ''),
            'mail'  => array('type' => 'string', 'default' => '')
        );
    }
}

Business::applyFilter('save', function($self, $params, $chain) {
    return Items::addTimestamps($self, $params, $chain);
});

Business::applyFilter('save', function($self, $params, $chain) {
    return Items::addGeocode($self, $params, $chain);
});

Business::finder('nearest', function($self, $params, $chain) {
    return Items::nearestFinder($self, $params, $chain);
});

Business::finder('within', function($self, $params, $chain) {
    return Items::withinFinder($self, $params, $chain);
});
