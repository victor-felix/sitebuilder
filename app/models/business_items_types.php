<?php

class BusinessItemsTypes {
    public static $inputTypes = array(
        'char' => 'text',
        'text' => 'textarea',
        'number' => 'text',
        'date' => 'text'
    );
    
    public function all() {
        return Config::read('BusinessItemsTypes');
    }
    
    public function firstById($id) {
        $segments = $this->all();
        return (object) $segments[$id];
    }
}