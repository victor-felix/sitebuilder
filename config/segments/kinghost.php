<?php

Config::write('Segments', array_merge(Config::read('Segments'), array(
  'kinghost' => array(
    'title' => 'kinghost',
    'items' => array('articles,', 'business'),
    'root' => 'index',
    'email' => array('no-reply@meumobi.com' => 'kinghost'),
		'extensions' =>array('store-locator', 'rss'),
		'hideCategories' => 0,
    'enableSignup' => 0,
    'primaryColor' => '#7665A9'
  )
)));
