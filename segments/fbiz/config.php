<?php

Config::write('Segment', array(
  'id' => 'fbiz',
  'title' => 'f.biz',
  'items' => array('articles', 'business'),
  'extensions' => array('store-locator', 'rss'),
  'root' => 'Index',
  'email' => array('no-reply@fbiz.com.br' => 'f.biz'),
  'hideCategories' => false,
  'enableSignup' => false,
));
