<?php

Config::write('Segment', array(
  'id' => 'plusclientes',
	'contactFacebook' => 'https://www.facebook.com/Plus-Clientes-605586442961556',
	'contactMail' => 'contact@ibrainholding.com',
	'title' => 'PlusClientes',
  'items' => array('articles'),
  'extensions' => array('rss'),
  'root' => 'produtos',
  'email' => array('contact@meumobi.com' => 'PlusClientes'),
  'hideCategories' => true,
  'enableSignup' => false,
  'fullOptions' => false,
	'analytics' => '',
	'enableFieldSet' => array('photos', 'location', 'contact', 'weblinks', 'description', 'timetable')
));
