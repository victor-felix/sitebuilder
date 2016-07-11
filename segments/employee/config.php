<?php

Config::write('Segment', array(
  'id' => 'employee',
  'title' => 'Employee',
  'contactMail' => 'contact@meumobi.com',
	'contactFacebook' => 'http://facebook.com/meumobi',
	'contactTwitter' => 'http://twitter.com/meumobi',
	'downloadAppUrl' => 'https://launchkit.io/websites/Qgoboi9Cb-E/',
  'items' => array('articles', 'polls', 'events'),
  'extensions' => array('rss'),
  'root' => 'index',
  'email' => array('contact@meumobi.com' => 'meumobi Employee'),
  'hideCategories' => false,
  'enableSignup' => false,
  'fullOptions' => true,
  'analytics' => 'UA-22519238-3',
	'enableFieldSet' => array('visitors', 'weblinks', 'description', 'contact'),
	'enableApiAccessFromAllDomains' => true,
	'domain' => 'meumobi.com'
));
