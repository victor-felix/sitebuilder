<?php

class Segments {
    public function all() {
        return Config::read('Segments');
    }
    
    public function firstById($id) {
        $segments = $this->all();
        return (object) $segments[$id];
    }
    
    public function toList() {
        $segments = $this->all();
        $normalized = array();
        
        foreach($segments as $slug => $segment) {
            $normalized[$slug] = $segment['title'];
        }
        
        return $normalized;
    }
}