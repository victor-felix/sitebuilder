<?php

Config::write('Segment', array(
  'id' => 'enterprise',
  'title' => 'MeuMobi Enterprise',
  'items' => array('articles', 'events'),
  'extensions' => array('store-locator', 'event-feed', 'rss', 'google-merchant-feed'),
  'root' => 'Index',
  'email' => array('no-reply@meumobi.com' => 'MeuMobi Enterprise'),
  'hideCategories' => false,
  'enableSignup' => true,
  'primaryColor' => '#000',
  'analytics' => 'UA-22519238-3',
  'themes' => array('paraty', 'posto9', 'leblon', 'helmut', 'flip', 'rimobi', 'flip_app', 'rimobi'),
  'enableFieldSet' => array('photos','weblinks','location', 'contact', 'news', 'description', 'timetable', 'stocks'),
));
