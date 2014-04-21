<?php

Config::write('Segment', array(
  'id' => 'investors',
  'title' => 'MeuMobi Investors',
  'items' => array('articles', 'events'),
  'extensions' => array('rss'),
  'root' => 'Index',
  'email' => array('contact@meumobi.com' => 'Investors'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => '',
));
