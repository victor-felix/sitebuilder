<?php

Config::write('Segment', array(
  'id' => 'kinghost',
  'title' => 'KingHost',
  'items' => array('articles', 'business'),
  'extensions' => array('store-locator', 'rss'),
  'root' => 'Index',
  'email' => array('no-reply@meumobi.com' => 'KingHost'),
  'hideCategories' => false,
  'enableSignup' => false,
  'primaryColor' => '#7665A9'
));
