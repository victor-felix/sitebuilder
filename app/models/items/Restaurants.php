<?php

namespace app\models\items;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

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
        )
    );

    public static function __init() {
        parent::__init();

        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + array(
            'geo'  => array('type' => 'array', 'default' => array()),
            'address'  => array('type' => 'string', 'default' => ''),
            'phone'  => array('type' => 'string', 'default' => ''),
            'price'  => array('type' => 'string', 'default' => '')
        );
    }

    public function fields($entity) {
        return array_merge(parent::fields($entity), array('address', 'phone', 
            'price'));
    }
}

Restaurants::applyFilter('save', function($self, $params, $chain) {
    $item = $params['entity'];

    if(!$item->id) {
        $item->created = date('Y-m-d H:i:s');
    }
    $item->modified = date('Y-m-d H:i:s');

    if(!empty($item->address)) {
        try {
            $geocode = GoogleGeocoding::geocode($item->address);
            $location = $geocode->results[0]->geometry->location;
            $item->geo = array($location->lng, $location->lat);
        }
        catch(Exception $e) {
            $item->geo = array(0, 0);
        }
    }
    else {
        $item->geo = array(0, 0);
    }

    return $chain->next($self, $params, $chain);
});

Restaurants::finder('nearest', function($self, $params, $chain) {
    $EARTH = 6378; // both in km
    $DISTANCE = 10;

    $lat = (float) array_unset($params['options']['conditions'], 'lat');
    $lng = (float) array_unset($params['options']['conditions'], 'lng');
    $geo = array(
        '$nearSphere' => array($lng, $lat),
        '$maxDistance' => $DISTANCE / $EARTH
    );

    $params['options']['conditions']['geo'] = $geo;

    return $chain->next($self, $params, $chain);
});

Restaurants::finder('within', function($self, $params, $chain) {
    $ne_lat = (float) array_unset($params['options']['conditions'], 'ne_lat');
    $ne_lng = (float) array_unset($params['options']['conditions'], 'ne_lng');
    $sw_lat = (float) array_unset($params['options']['conditions'], 'sw_lat');
    $sw_lng = (float) array_unset($params['options']['conditions'], 'sw_lng');

    $lower_left = array($sw_lng, $sw_lat);
    $upper_right = array($ne_lng, $ne_lat);

    $geo = array(
        '$within' => array('$box' => array($lower_left, $upper_right))
    );

    $params['options']['conditions']['geo'] = $geo;

    return $chain->next($self, $params, $chain);
});
