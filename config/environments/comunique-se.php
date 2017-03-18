<?php

Config::write('Yaml.cache', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.mobi.comunique-se.com.br');
Config::write('Sites.domain', 'mobi.comunique-se.com.br');
Config::write('Themes.url', 'http://meu-template-repository.mobi.comunique-se.com.br/config/themes.json');

Config::write('PushWoosh.debug', false);
Config::write('PushWoosh.appIds', [
	[ 'site_id' => 15, 'app_id' => 'E2137-A18C0' ], //Cielo
	[ 'site_id' => 22, 'app_id' => '1C738-F9398' ], //Embraer
	[ 'site_id' => 61, 'app_id' => 'C508A-081E1' ], //RaiaDrogasil
	[ 'site_id' => 10, 'app_id' => 'C04E8-7DDEA' ], //Triunfo
]);

Config::write('Status.sites', [
	'cieloen.mobi.comunique-se.com.br',
]);
