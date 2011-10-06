<?php

require_once 'app/models/business_items.php';

class Restaurants extends BusinessItems {
    protected $geocoding = true;
		protected $typeName = 'Restaurant';
    protected $fields = array(
        'lat' => array(),
				'lng' => array(),
				'title' => array(
            'title' => 'Name',
            'type' => 'string'
        ),
/*        'description' => array(
            'title' => 'Description',
            'type' => 'richtext'
        ),
*/
				'address' => array(
            'title' => 'Location',
            'type' => 'text'
        ),
        'phone' => array(
            'title' => 'Phone',
            'type' => 'string'
        ),
        'price' => array(
            'title' => 'Price',
            'type' => 'string'
        ),
    );
		
		public function fields() {
        return array('title', 'address', 'phone', 'price');
		}
}
