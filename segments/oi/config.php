<?php

Config::write('Segment', array(
  'id' => 'oi',
  'title' => 'Oi',
  'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores', 'users'),
  'extensions' => array('store-locator', 'rss'),
  'root' => 'Index',
  'email' => array('no-reply@oi.com' => 'Oi'),
  'hideCategories' => false,
  'enableSignup' => false,
  'primaryColor' => '#000'
));
