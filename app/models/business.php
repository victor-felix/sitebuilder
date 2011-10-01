<?php

require_once 'app/models/business_items.php';

class Business extends BusinessItems {
    protected $typeName = 'Business';
    protected $fields = array(
        'title' => array(
            'title' => 'Name',
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
        'phone' => array(
            'title' => 'Phone',
            'type' => 'string'
        ),
        'web' => array(
            'title' => 'Website Url',
            'type' => 'string'
        ),
        'mail' => array(
            'title' => 'Mail',
            'type' => 'string'
        )
    );
}
