<?php

Config::write('Segments', array_merge(Config::read('Segments'), array(
  'meumobi' => array(
    'title' => 'MeuMobi',
    'items' => array('articles', 'business'),
    'root' => 'Index',
    'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
    'hideCategories' => 0,
    'enableSignup' => 0,
    'primaryColor' => '#ccc'
  )
)));
