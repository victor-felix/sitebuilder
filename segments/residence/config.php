<?php

Config::write('Segment', array(
  'id' => 'residence',
  'title' => 'meumobi Residence',
  'contactMail' => 'contact@meumobi.com',
	'contactFacebook' => 'http://facebook.com/meumobi',
	'contactTwitter' => 'http://twitter.com/meumobi',
	'items' => array('articles', 'polls', 'events'),
  'extensions' => array('rss'),
  'root' => 'index',
  'email' => array('contact@meumobi.com' => 'meumobi Residence'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => 'UA-22519238-3',
));
