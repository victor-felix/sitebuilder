<?php

require_once 'app/models/business_items.php';

class Links extends BusinessItems {
    protected $typeName = 'Link';
    protected $fields = array(
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'url' => array(
            'title' => 'Link',
            'type' => 'string',
            'validates' => array(
                'rule' => array('urlWithProtocol', 'url'),
                'message' => 'You should provide a valid link'
            )
        )
    );

    public function urlWithProtocol($value, $field) {
        if(!preg_match('%^https?://%', $value)) {
            $this->data[$field] = $value = 'http://' . $value;
        }

        return Validation::url($value);
    }
}
