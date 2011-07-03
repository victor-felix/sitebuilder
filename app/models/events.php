<?php

class Events extends BusinessItems {
    protected $typeName = 'Event';
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
}
