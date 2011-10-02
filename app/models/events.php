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
