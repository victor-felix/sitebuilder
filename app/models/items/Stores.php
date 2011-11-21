<?php

namespace app\models\items;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

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
            'geo'  => array('type' => 'array', 'default' => array()),
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

Stores::finder('nearest', function($self, $params, $chain) {
    $EARTH = 6378; // both in km
    $DISTANCE = 10;

    $lat = (float) array_unset($params['options']['conditions'], 'lat');
    $lng = (float) array_unset($params['options']['conditions'], 'lng');
    $geo = array(
        '$near' => array($lng, $lat),
        //'$nearSphere' => array($lng, $lat),
        //'$maxDistance' => $DISTANCE / $EARTH
    );

    $params['options']['conditions']['geo'] = $geo;

    return $chain->next($self, $params, $chain);
});

Stores::finder('within', function($self, $params, $chain) {
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
