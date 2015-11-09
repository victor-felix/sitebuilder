<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'domain' => 'meumobi.com',
  'title' => 'MeuMobi',
  'root' => 'Index',
  'items' =>  Config::read('App.environment') == 'production' ? ['articles'] : ['articles', 'events', 'merchant_products', 'promotions', 'products', 'stores', 'files'],
  'extensions' => Config::read('App.environment') == 'production' ? ['rss'] : ['rss', 'store-locator', 'event-feed','google-merchant-feed'],
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => false,
  'enableSignup' => true,
  'fullOptions' => false,
  'enableMultiUsers' => Config::read('App.environment') != 'production',
  'analytics' => 'UA-22519238-3',
  'themes' => Config::read('App.environment') == 'production' ? array('paraty', 'posto9', 'leblon', 'helmut', 'flip', 'rimobi') : [],
  'enableFieldSet' => array('photos', 'visitors', 'weblinks','location', 'contact', 'news', 'description', 'timetable'), //, 'stocks'),
  'enableApiAccessFromAllDomains' => true,
));
