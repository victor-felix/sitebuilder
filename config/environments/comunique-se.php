<?php

Config::write('Yaml.cache', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.mobi.comunique-se.com.br');
Config::write('Sites.domain', 'mobi.comunique-se.com.br');
Config::write('Themes.url', 'http://meu-template-repository.mobi.comunique-se.com.br/config/themes.json');

Config::write('PushWoosh.debug', false);

Config::write('Status.sites', [
	'cieloen.mobi.comunique-se.com.br',
]);
