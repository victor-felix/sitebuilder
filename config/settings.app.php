<?php

Config::write('Sites.blacklist', array());

Config::write('SiteLogos.resizes', array('200x200'));
Config::write('SitePhotos.resizes', array('80x80#', '139x139#'));
Config::write('BusinessItems.resizes', array('80x60#', '85x85#', '80x80#', '30x30#', '139x139#', '173x154#'));

Config::write('Segments', array(
    'example' => array(
        'title' => 'Example Segment',
        'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores'),
        'root' => 'Index',
        'email' => array('no-reply@example.com' => 'Example'),
        'hideCategories' => 1
    ),
    '1440group' => array(
        'title' => '1440group',
        'items' => array('articles', 'links'),
        'root' => 'Index',
        'email' => array('no-reply@1440group.com' => '1440group'),
        'hideCategories' => 0
    )
));

Config::write('Themes.url', 'http://meu-template-engine.meumobi.com/api/index');
