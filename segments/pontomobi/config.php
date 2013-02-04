<?php

Config::write('Segment', array(
  'id' => 'pontomobi',
  'title' => 'pontomobi',
  'items' => array('articles', 'business'),
  'extensions' => array('store-locator', 'rss'),
  'root' => 'Index',
  'email' => array('no-reply@pontomobi.com' => 'pontomobi'),
  'hideCategories' => false,
  'enableSignup' => 0,
  'primaryColor' => ''
));
