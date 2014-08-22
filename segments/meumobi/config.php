<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'title' => 'MeuMobi',
  'root' => 'Index',
  'items' => array('articles', 'business', 'links', 'products', 'promotions', 'restaurants', 'stores', 'events', 'extended_articles', 'users', 'merchant_products'),
  'extensions' => array('rss', 'store-locator', 'event-feed','google-merchant-feed'),
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => false,
  'enableSignup' => true,
  'fullOptions' => false,
  'enableMultiUsers' => false,
  'analytics' => 'UA-22519238-3',
  'themes' => array('paraty', 'posto9', 'leblon', 'helmut', 'flip', 'rimobi'), //'flip_app', 'rimobi'),
  'enableFieldSet' => array('photos','weblinks','location', 'contact', 'news', 'description', 'timetable', 'stocks'),
));
