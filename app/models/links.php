<?php

class Links extends BusinessItems {
    protected $typeName = 'Link';
    protected $fields = array(
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'url' => array(
            'title' => 'Link',
            'type' => 'string'
        )
    );
}
