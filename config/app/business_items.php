<?php

/**
 * available field_types:
 * - char
 * - text
 * - number
 * - date
 * - hour
 * - boolean
 */

Config::write('BusinessItemsTypes', array(
    'products' => array(
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
                'field_type' => 'text'
            ),
            'featured' => array(
                'title' => 'Destaque?',
                'field_type' => 'boolean'
            )
        )
    ),
    'events' => array(
        'title' => 'Evento',
        'fields' => array(
            'title' => array(
                'title' => 'Título',
                'field_type' => 'char',
                'required' => true,
                'limit' => 100
            ),
            'description' => array(
                'title' => 'Descrição',
                'field_type' => 'text'
            ),
            'address' => array(
                'title' => 'Endereço',
                'field_type' => 'text',
                'limit' => 500
            ),
            'contact' => array(
                'title' => 'Contato',
                'field_type' => 'text',
                'limit' => 500
            ),
            'date' => array(
                'title' => 'Data',
                'field_type' => 'date'
            ),
            'hour' => array(
                'title' => 'Hora',
                'field_type' => 'hour'
            )
        )
    ),
    'articles' => array(
        'title' => 'Artigo',
        'fields' => array(
            'title' => array(
                'title' => 'Título',
                'field_type' => 'char',
                'required' => true,
                'limit' => 100
            ),
            'description' => array(
                'title' => 'Descrição',
                'field_type' => 'text'
            ),
            'author' => array(
                'title' => 'Autor',
                'field_type' => 'char',
                'limit' => 100
            )
        )
    ),
));
