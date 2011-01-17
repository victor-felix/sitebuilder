<?php

Config::write('BusinessItems', array(
    'product' => array(
        'title' => 'Restaurante',
        'fields' => array(
            'title' => array(
                'title' => 'Título',
                'field_type' => 'char'
            ),
            'description' => array(
                'title' => 'Descrição',
                'field_type' => 'text'
            )
        )
    )
));