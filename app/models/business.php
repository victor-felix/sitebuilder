<?php

require_once 'app/models/business_items.php';

class Business extends BusinessItems {
    protected $geocoding = true;
		protected $typeName = 'Business';
    protected $fields = array(
        'lat' => array(),
        'lng' => array(),
				'title' => array(
            'title' => 'Name',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Activity',
            'type' => 'text'
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
		
		public function fields() {
        return array('title', 'description', 'address', 'phone', 'web', 'mail');
		}
}
