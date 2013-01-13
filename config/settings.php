<?php

Config::write('App.environment', trim(Filesystem::read(__DIR__ . '/ENVIRONMENT')));
Config::write('Security.salt', 'a5d5d5be3c69dbc8b49e3342db0f8952f6328abd67076bf65d7c3c67a1fbfcab4946aa0fbd6b506db958449ac5e81637d0f5b8ee88e4d0760909cabe2e78137c');
Config::write('Mailer.transport', 'mail');

Config::write('Sites.blacklist', array());

Config::write('Geocode.urls', array(
	'http://maps.googleapis.com',
	'http://elefante.ipanemax.com',
	'http://laguna.ipanemax.com',
	'http://branca.ipanemax.com',
	'http://bonita.ipanemax.com',
));

Config::write('Preview.url', 'http://placeholder.meumobi.com');
Config::write('SiteLogos.resizes', array('200x200'));
Config::write('SitePhotos.resizes', array('139x139#', '314x220'));
Config::write('BusinessItems.resizes', array('80x60#', '80x80#', '139x139#', '314x220'));

Config::write('Sites.domain', 'int-meumobi.com');
Config::write('multiInstances', 1);
$dir = new GlobIterator(__DIR__ . '/segments/*.php');
foreach($dir as $file) {
    if($file->isFile()) {
        require $file->getPathname();
    }
}

require 'config/environments/' . Config::read('App.environment') . '.php';
