<?php

Config::write('Segment', array(
	//'aboutUsUrl' => '',
	// 'blogUrl' => '',
	'contactMail' => 'contact@meumobi.com',
	// 'contactPhone' => '',
	'contactFacebook' => 'http://facebook.com/meumobi',
	'contactTwitter' => 'http://twitter.com/meumobi',
  'id' => 'enterprise',
  'title' => 'MeuMobi Enterprise',
  'items' => array('articles', 'events'),
  'extensions' => array('store-locator', 'event-feed', 'rss', 'google-merchant-feed'),
  'root' => 'Index',
  'email' => array('no-reply@meumobi.com' => 'MeuMobi Enterprise'),
  'hideCategories' => false,
  'enableSignup' => false,
  'primaryColor' => '#000',
  'analytics' => 'UA-22519238-3',
  'themes' => array('paraty', 'posto9', 'leblon', 'helmut', 'flip', 'rimobi', 'flip_app', 'rimobi'),
  'enableFieldSet' => array('photos','weblinks','location', 'contact', 'news', 'description', 'timetable', 'stocks'),
  'enableApiAccessFromAllDomains' => true
));
