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

    public static function validate($field, $value) {
        $defaults = array(
            'required' => false,
            'limit' => false
        );
        $field = array_merge($defaults, $field);
        
        $validations = array(
            self::validatePresence($value, $field),
            self::validateLength($value, $field),
            self::validateType($value, $field)
        );
        
        foreach($validations as $validation) {
            if($validation !== true) {
                return $validation;
            }
        }
        
        return array(true, array());
    }
    
    protected static function validateLength($value, $field) {
        if($field['limit'] && strlen($value) > $field['limit']) {
            return array(false, $field['title'] . ' pode ter no máximo ' . $field['limit'] . ' caracteres');
        }
        else {
            return true;
        }
    }
    
    protected static function validatePresence($value, $field) {
        if($field['required'] && empty($value)) {
            return array(false, 'Você precisa informar um valor para ' . strtolower($field['title']));
        }
        else {
            return true;
        }
    }
    
    protected static function validateType($value, $field) {
        $method = 'validate' . Inflector::camelize($field['field_type']);
        
        return self::$method($value, $field);
    }
    
    protected static function validateChar($value, $field) {
        return true;
    }

    protected static function validateText($value, $field) {
        return true;
    }

    protected static function validateBoolean($value, $field) {
        return true;
    }

    protected static function validateNumber($value, $field) {
        if(!preg_match('/^\d+(,\d{1,2})?$/', $value)) {
            return array(false, $field['title'] . ' só pode conter números e deve usar vírgulas como separador decimal');
        }
        else {
            return true;
        }
    }

    protected static function validateDate($value, $field) {
        if(!preg_match('%^\d{1,2}/\d{1,2}/\d{4}$%', $value)) {
            return array(false, $field['title'] . ' deve estar no formato DD/MM/YYYY');
        }
        else {
            return true;
        }
    }
}