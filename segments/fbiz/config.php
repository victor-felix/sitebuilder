<?php

Config::write('Segment', array(
	'id' => 'fbiz',
	'title' => 'f.biz',
	'items' => array('articles', 'business', 'merchant_products'),
	'extensions' => array('store-locator', 'rss', 'google-merchant-feed'),
	'root' => 'Index',
	'email' => array('no-reply@fbiz.com.br' => 'f.biz'),
	'hideCategories' => false,
	'enableSignup' => false,
	'analytics' => 'UA-22519238-5',
));
