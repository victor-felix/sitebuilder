<?php

Config::write('Yaml.cache', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.meumobi.com');
Config::write('Sites.domain', 'meumobi.com');

Config::write('Themes.url', 'http://meu-template-engine.meumobi.com/themes.json');

Config::write('PushWoosh.debug', false);
Config::write('PushWoosh.appIds', [
	[ 'site_id' => 515, 'app_id'=> 'A9FC4-681E3' ], //katrium
]);

Config::write('Status.sites', [
	'180back.meumobi.com',
	'infobox.meumobi.com',
	'dnadigital.meumobi.com',
]);
