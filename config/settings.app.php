<?php

Config::write('Sites.blacklist', array());

Config::write('SiteLogos.resizes', array('200x200'));
Config::write('SitePhotos.resizes', array('80x80#', '139x139#'));
Config::write('BusinessItems.resizes', array('80x60#', '85x85#', '80x80#', '30x30#', '139x139#', '173x154#'));

Config::write('Segments', array(
    'example' => array(
        'title' => 'Example Segment',
        'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores'),
        'root' => 'Index'
    )
));

Config::write('Themes.url', 'http://meu-template-engine.meumobi.com/api/index');
