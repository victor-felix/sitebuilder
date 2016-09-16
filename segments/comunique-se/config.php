<?php

Config::write('Themes.url', 'http://comunique-se.meumobilesite.com/themes.json');

Config::write('Segment', array(
	//'aboutUsUrl' => '',
	// 'blogUrl' => '',
	'contactMail' => 'contact@meumobi.com',
	// 'contactPhone' => '',
	'contactFacebook' => 'http://facebook.com/meumobi',
	'contactTwitter' => 'http://twitter.com/meumobi',
	'downloadAppUrl' => 'https://launchkit.io/websites/Qgoboi9Cb-E/',
	'id' => 'comunique-se',
	'title' => 'comunique-se',
	'items' => array('articles', 'polls'),
	'extensions' => array('rss'),
	'root' => 'index',
	'email' => array('no-reply@meumobi.com' => 'comunique-se'),
	'hideCategories' => false,
	'enableSignup' => false,
	'fullOptions' => true,
	'analytics' => 'UA-22519238-16',
	'enableFieldSet' => array('visitors', 'contact'),
	'enableSubCategories' => false,
	'enableApiAccessFromAllDomains' => true,
	'domain' => 'meumobi.com'
));
