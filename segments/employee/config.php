<?php

Config::write('Themes.url', 'http://employee.meumobilesite.com/themes.json');

Config::write('Segment', array(
  'id' => 'employee',
  'title' => 'Employee',
  'contactMail' => 'contact@meumobi.com',
	'contactFacebook' => 'http://facebook.com/meumobi',
	'contactTwitter' => 'http://twitter.com/meumobi',
	'downloadAppUrl' => 'https://launchkit.io/websites/Qgoboi9Cb-E/',
  'items' => array('articles', 'polls', 'events', 'contacts'),
  'extensions' => array('rss'),
  'root' => 'index',
  'email' => array('contact@meumobi.com' => 'meumobi Employee'),
  'hideCategories' => false,
  'enableSignup' => true,
  'fullOptions' => true,
  'analytics' => 'UA-22519238-3',
	'enableFieldSet' => array('visitors', 'contact'),
	'enableSubCategories' => false,
	'enableApiAccessFromAllDomains' => true,
	'domain' => 'meumobi.com'
));
