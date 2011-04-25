<?php

class Events extends BusinessItems {
    protected $fields = array(
        'title' => array(
            'title' => 'Título',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Descrição',
            'type' => 'richtext'
        ),
        'address' => array(
            'title' => 'Endereço',
            'type' => 'text'
        ),
        'contact' => array(
            'title' => 'Contato',
            'type' => 'text'
        ),
        'date' => array(
            'title' => 'Data',
            'type' => 'string'
        ),
        'hour' => array(
            'title' => 'Hora',
            'type' => 'string'
        )
    );
}
