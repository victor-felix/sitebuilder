<?php

Config::write('Segment', array(
  'id' => 'infobox',
  'title' => 'infobox',
  'items' => array('articles', 'events'),
  'extensions' => array('rss'),
  'root' => 'Posts',
  'email' => array('infobox@meumobi.com' => 'Infobox'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => '',
	'enableFieldSet' => array('photos', 'visitors', 'weblinks','location', 'contact', 'news', 'description', 'timetable'),
  'enableApiAccessFromAllDomains' => true,
));
