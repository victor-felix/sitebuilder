<?php

Config::write('Segment', array(
  'id' => 'ageisobar',
  'title' => 'Ageisobar',
  'items' => array('articles', 'business'),
  'extensions' => array('store-locator', 'rss'),
  'root' => 'Index',
  'email' => array('no-reply@ageisobar.com.br' => 'Ageisobar'),
  'hideCategories' => false,
  'enableSignup' => 0,
  'primaryColor' => ''
));
