<?php

Config::write('Segments', array_merge(Config::read('Segments'), array(
	'meumobi' => array(
		'title' => 'MeuMobi',
		'items' => array('articles', 'business', 'users'),
		'root' => 'Index',
		'email' => array('no-reply@meumobi.com' => 'MeuMobi'),
		'hideCategories' => 0,
		'enableSignUp' => 0,
		'fullOptions' => 0,
		'primaryColor' => '#ccc',
	)
)));
