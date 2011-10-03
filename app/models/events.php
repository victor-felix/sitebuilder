<?php

require_once 'app/models/business_items.php';

class Events extends BusinessItems {
    protected $geocoding = true;
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
}
