<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'title' => 'MeuMobi',
  'root' => 'Index',
  'items' => array('articles', 'business', 'users'),
  'extensions' => array('rss','store-locator'),
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => false,
  'enableSignup' => true,
  'fullOptions' => false,
  'enableMultiUsers' => false
));
