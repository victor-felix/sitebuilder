<?php

/**
 * available field_types:
 * - char
 * - text
 * - number
 * - date
 * - boolean
 */

Config::write('BusinessItemsTypes', array(
    'product' => array(
        'title' => 'Produto',
        'fields' => array(
            'title' => array(
                'title' => 'Título',
                'field_type' => 'char',
                'required' => true,
                'limit' => 100
            ),
            'price' => array(
                'title' => 'Preço',
                'field_type' => 'number'
            ),
            'description' => array(
                'title' => 'Descrição',
                'field_type' => 'text',
                'limit' => 500
            )
        )
    )
));