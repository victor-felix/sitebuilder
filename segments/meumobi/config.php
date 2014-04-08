<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'title' => 'MeuMobi',
  'root' => 'Index',
  'items' => array('articles','events', 'extended_articles'),
  'extensions' => array('rss', 'store-locator', 'event-feed'),
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => false,
  'enableSignup' => true,
  'fullOptions' => false,
  'enableMultiUsers' => false,
  'analytics' => 'UA-22519238-3',
  'themes' => array('paraty', 'posto9', 'leblon', 'helmut', 'flip', 'flip_app', 'rimobi')
));
