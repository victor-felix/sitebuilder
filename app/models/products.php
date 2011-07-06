<?php

require_once 'app/models/business_items.php';

class Products extends BusinessItems {
    protected $typeName = 'Product';
    protected $fields = array(
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'price' => array(
            'title' => 'Price',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Description',
            'type' => 'richtext'
        ),
        'featured' => array(
            'title' => 'Featured?',
            'type' => 'boolean'
        )
    );
}
