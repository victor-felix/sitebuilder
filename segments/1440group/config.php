<?php

Config::write('Segment', array(
  'id' => '1440group',
  'title' => '1440group',
  'items' => array('articles', 'business'),
  'extensions' => array('store-locator', 'rss'),
  'root' => 'Index',
  'email' => array('no-reply@1440group.com' => '1440group'),
  'hideCategories' => false,
  'enableSignup' => false,
  'primaryColor' => '#000'
));
