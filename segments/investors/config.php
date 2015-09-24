<?php

Config::write('Segment', array(
  'id' => 'investors',
  'title' => 'MeuMobi Investors',
  'items' => array('articles', 'events'),
  'extensions' => array('rss', 'event-feed'),
  'root' => 'Index',
  'email' => array('contact@meumobi.com' => 'Investors'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => '',
  'enableMultiUsers' => true,
  'analytics' => 'UA-22519238-3',
'themes' => array('rimobi'),
'enableFieldSet' => array('stocks', 'photos', 'location', 'contact', 'description'),
'enableApiAccessFromAllDomains' => true
));
