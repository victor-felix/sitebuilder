<?php

class Links extends BusinessItems {
    protected $typeName = 'Link';
    protected $fields = array(
        'title' => array(
            'title' => 'Título',
            'type' => 'string'
        ),
        'url' => array(
            'title' => 'Link',
            'type' => 'string'
        )
    );
}
