<?php

Config::write('Segment', array(
	'id' => 'ipanemax',
	'title' => 'Ipanemax',
	'items' => array('articles', 'events', 'products', 'links', 'business', 'restaurants', 'stores', 'users'),
	'root' => 'Index',
	'email' => array('no-reply@ipanemax.com' => 'ipanemax'),
	'extensions' =>array('store-locator', 'rss'),
	'hideCategories' => 0,
	'enableSignUp' => 1,
	'sitePreviewUrl' => 'http://placeholder.int-meumobi.com'
));
