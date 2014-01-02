<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'title' => 'MeuMobi',
  'root' => 'Index',
  'items' => array('articles', 'business', 'products'),
  'extensions' => array('rss','store-locator'),
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => false,
  'enableSignup' => true,
  'fullOptions' => false,
  'enableMultiUsers' => false,
  'analytics' => 'UA-22519238-3',
  'themes' => array('paraty', 'posto9', 'leblon', 'helmut', 'flip')
));
