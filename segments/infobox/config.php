<?php

Config::write('Segment', array(
  'id' => 'infobox',
  'title' => 'infobox',
  'items' => array('articles', 'events', 'polls'),
  'extensions' => array('rss'),
  'root' => 'Posts',
  'email' => array('infobox@meumobi.com' => 'InfoBox'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => '',
  'enableFieldSet' => array('visitors', 'weblinks', 'news', 'description', 'contact'),
  'enableApiAccessFromAllDomains' => true,
  'domain' => 'infobox.meumobilesite.com',
));
