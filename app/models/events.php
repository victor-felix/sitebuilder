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
            $distance = $item->distance($lat, $lng);
            if($distance < 0.5) {
                $nearest []= $item;
            }
        }

        return $nearest;
    }

    public function area($category_id, $ne_lat, $ne_lng, $sw_lat, $sw_lng) {
        $items = $this->allByParentId($category_id);
        $area = array();

        foreach($items as $item) {
            if($item->inside($ne_lat, $ne_lng, $sw_lat, $sw_lng)) {
                $area []= $item;
            }
        }

        return $area;
    }

    protected function distance($lat, $lng) {
        $values = $this->values();
        if(!empty($values->lat) && !empty($values->lng)) {
            $distance = sqrt(pow($lat - $values->lat, 2) + pow($lng - $values->lng, 2));
            return $distance;
        }
    }

    protected function inside($ne_lat, $ne_lng, $sw_lat, $sw_lng) {
        $s = $this->values();
        return ($s->lat >= $sw_lat && $s->lat <= $ne_lat) &&
            ($s->lng >= $sw_lng && $s->lng <= $ne_lng);
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
