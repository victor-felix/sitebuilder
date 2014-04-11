<?php

Config::write('Segment', array(
  'id' => 'rimobi',
  'title' => 'RImobi',
  'items' => array('articles', 'events'),
  'extensions' => array('rss'),
  'root' => 'Index',
  'email' => array('mobile@comunique-se.com.br' => 'RImobi'),
  'hideCategories' => false,
  'enableSignup' => false,
  'primaryColor' => '#000'
));
