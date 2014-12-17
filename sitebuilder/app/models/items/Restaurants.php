<?php

namespace app\models\items;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

use app\models\Items;

class Restaurants extends \app\models\Items {
    protected $type = 'Restaurant';

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
        'price' => array(
            'title' => 'Price',
            'type' => 'string'
        ),
        'group' => array(
            'title' => 'Group',
            'type' => 'string'
        ),
    );

    public static function __init() {
        parent::__init();

        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + array(
            'related'  => array('type' => 'array', 'default' => array()),
            'geo'  => array('type' => 'array', 'default' => 0),
            'ratings'  => array('type' => 'hash', 'default' => array()),
            'address'  => array('type' => 'string', 'default' => ''),
            'phone'  => array('type' => 'string', 'default' => ''),
            'price'  => array('type' => 'string', 'default' => '')
        );
    }
}

Restaurants::applyFilter('save', function($self, $params, $chain) {
    return Items::addTimestamps($self, $params, $chain);
});

Restaurants::applyFilter('save', function($self, $params, $chain) {
    return Items::addGeocode($self, $params, $chain);
});

Restaurants::finder('nearest', function($self, $params, $chain) {
    return Items::nearestFinder($self, $params, $chain);
});

Restaurants::finder('within', function($self, $params, $chain) {
    return Items::withinFinder($self, $params, $chain);
});

Restaurants::applyFilter('save', function($self, $params, $chain) {
	return Items::addOrder($self, $params, $chain);
});
