<?php

Config::write('Segment', array(
  'id' => 'infobox',
  'title' => 'infobox',
  'items' => array('articles', 'events'),
  'extensions' => array('rss'),
  'root' => 'Posts',
  'email' => array('victor@meumobi.com' => 'infobox'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => '',
  'enableApiAccessFromAllDomains' => true,
));
