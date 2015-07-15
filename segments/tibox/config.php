<?php

Config::write('Segment', array(
  'id' => 'tibox',
  'title' => 'tibox',
  'items' => array('articles'),
  'extensions' => array('rss'),
  'root' => 'index',
  'email' => array('no-reply@meumobi.com' => 'tibox'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => 'UA-22519238-15',
));
