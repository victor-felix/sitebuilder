<?php

Config::write('Segments', array(
    'restaurant' => array(
        'title' => 'Restaurante',
        'themes' => array(
            'govrj' => 'govrj'
        ),
        'skins' => array('ae3232', '278740', '326cae'),
        'items' => 'products',
        'root' => 'Cardápio'
    ),
    'events' => array(
        'title' => 'Events',
        'themes' => array(
            'govrj' => 'govrj'
        ),
        'skins' => array('ae3232', '278740', '326cae'),
        'items' => 'events',
        'root' => 'Agenda'
    ),
    'corporate' => array(
        'title' => 'Corporate',
        'themes' => array(
					'govrj' => 'govrj',
					'cahpcu' => 'cahpcu'
        ),
        'skins' => array('ae3232', '278740', '326cae'),
        'items' => array('articles', 'events', 'products', 'links'),
        'root' => 'Index'
    )
));
