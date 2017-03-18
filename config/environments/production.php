<?php

Config::write('Yaml.cache', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.meumobi.com');
Config::write('Sites.domain', 'meumobi.com');

Config::write('Themes.url', 'http://meu-template-repository.meumobi.com/config/themes.json');

Config::write('PushWoosh.debug', false);
Config::write('PushWoosh.appIds', [
  [ 'site_id' => 515, 'app_id'=> 'A9FC4-681E3' ], //katrium
  [ 'site_id' => 516, 'app_id'=> 'A9FC4-681E3' ], //meumobibox
  [ 'site_id' => 503, 'app_id'=> 'A9FC4-681E3' ], //dia
  [ 'site_id' => 539, 'app_id'=> 'A9FC4-681E3' ], //fcb
  [ 'site_id' => 540, 'app_id'=> 'A9FC4-681E3' ], //europ-assistance
  [ 'site_id' => 537, 'app_id'=> 'A9FC4-681E3' ], //SHHSJC
  [ 'site_id' => 536, 'app_id'=> 'A9FC4-681E3' ] //sinax
]);

Config::write('Status.sites', [
	'180back.meumobi.com',
	'infobox.meumobi.com',
	'dnadigital.meumobi.com',
]);
