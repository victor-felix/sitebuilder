<?php

require_once 'app/models/business_items.php';

class Stores extends BusinessItems {
    protected $geocoding = true;
		protected $typeName = 'Store';
    protected $fields = array(
        'lat' => array(),
        'lng' => array(),
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
        ),
        'hours' => array(
            'title' => 'Opening hours',
            'type' => 'text'
					),
				'featured' => array(
            'title' => 'Featured?',
            'type' => 'boolean'
        )
    );
		
		public function fields() {
        return array('title', 'description', 'address', 'phone', 'web', 'mail', 'hours', 'featured');
		}
}
