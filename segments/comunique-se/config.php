<?php

Config::write('Segment', array(
  'id' => 'comunique-se',
  'title' => 'comunique-se',
  'items' => array('articles'),
  'extensions' => array('rss'),
  'root' => 'index',
  'email' => array('no-reply@meumobi.com' => 'comunique-se'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => 'UA-22519238-16',
  'enableFieldSet' => array('visitors', 'weblinks', 'news', 'description', 'contact'),
  'enableApiAccessFromAllDomains' => true,
));
