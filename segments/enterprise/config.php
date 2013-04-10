<?php

Config::write('Segment', array(
  'id' => 'enterprise',
  'title' => 'MeuMobi Enterprise',
  'items' => array('articles', 'business'),
  'extensions' => array('store-locator', 'rss'),
  'root' => 'Index',
  'email' => array('no-reply@meumobi.com' => 'MeuMobi Enterprise'),
  'hideCategories' => false,
  'enableSignup' => false,
  'primaryColor' => '#000',
  'analytics' => 'UA-22519238-3'
));
