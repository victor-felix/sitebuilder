<?php

require_once 'app/models/business_items.php';

class Events extends BusinessItems {
    protected $beforeSave = array('setSiteValues', 'getOrder', 'getLatLng');
    protected $typeName = 'Event';
    protected $fields = array(
        'lat' => array(),
        'lng' => array(),
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Description',
            'type' => 'richtext'
        ),
        'address' => array(
            'title' => 'Location',
            'type' => 'text'
        ),
        'contact' => array(
            'title' => 'Contact',
            'type' => 'text'
        ),
        'date' => array(
            'title' => 'Date',
            'type' => 'string'
        ),
        'hour' => array(
            'title' => 'Time',
            'type' => 'string'
        )
    );

    public function fields() {
        return array('title', 'description', 'address', 'contact', 'date', 'hour');
    }

    public function nearest($category_id, $lat, $lng) {
        $items = $this->allByParentId($category_id);
        $nearest = array();

        foreach($items as $item) {
            $distance = $this->distance($item, $lat, $lng);
            if($distance < 0.5) {
                $nearest []= $item;
            }
        }

        return $nearest;
    }

    public function distance($item, $lat, $lng) {
        $values = $item->values();
        if(!empty($values->lat) && !empty($values->lng)) {
            $distance = sqrt(pow($lat - $values->lat, 2) + pow($lng - $values->lng, 2));
            return $distance;
        }
    }

    protected function getLatLng($data) {
        if(!empty($data['address'])) {
            try {
                $geocode = GoogleGeocoding::geocode($data['address']);
                $location = $geocode->results[0]->geometry->location;
                $data['lat'] = $location->lat;
                $data['lng'] = $location->lng;

                return $data;
            }
            catch(Exception $e) {}
        }

        $data['lat'] = $data['lng'] = '';

        return $data;
    }
}
