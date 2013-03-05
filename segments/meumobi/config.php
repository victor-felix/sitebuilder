<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'title' => 'MeuMobi',
  'items' => array('articles', 'business', 'users'),
  'extensions' => array('rss','store-locator'),
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => true,
  'enableSignup' => 1,
  'hideCategories' => true,
  'enableSignup' => true,
  'fullOptions' => false,
  'enableMultiUsers' => false
));
