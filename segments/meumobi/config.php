<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'title' => 'MeuMobi',
  'items' => array('articles', 'business', 'users'),
  'extensions' => array('rss','store-locator'),
  'root' => 'Index',
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => false,
  'enableSignup' => 1,
  'fullOptions' => false,
  'primaryColor' => '#ccc'
));
