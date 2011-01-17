<?php

class AppModel extends Model {
    public function toJSON() {
        return $this->data;
    }
    
    protected function unique($value, $field) {
        return !$this->count(array(
            'conditions' => array(
                $field => $value,
                'id <>' => $this->id
            )
        ));
    }
    
    protected function asciiOnly($value) {
        return preg_match('/^[\w._-]+$/', $value);
    }
}