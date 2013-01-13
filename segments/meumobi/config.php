<?php

Config::write('Segment', array(
  'id' => 'meumobi',
  'title' => 'MeuMobi',
  'items' => array('articles', 'business', 'users'),
  'extensions' => array(''),
  'root' => 'Index',
  'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => false,
  'primaryColor' => '#ccc'
));
