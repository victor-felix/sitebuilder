<?php

Config::write('BusinessItemsTypes', array(
    'product' => array(
        'title' => 'Produto',
        'fields' => array(
            'title' => array(
                'title' => 'Título',
                'field_type' => 'char'
            ),
            'price' => array(
                'title' => 'Preço',
                'field_type' => 'number'
            ),
            'description' => array(
                'title' => 'Descrição',
                'field_type' => 'text'
            )
        )
    )
));