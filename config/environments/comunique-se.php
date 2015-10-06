<?php

ini_set('error_reporting', 0);
ini_set('display_errors', 'Off');

Config::write('Yaml.cache', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.mobi.comunique-se.com.br');
Config::write('Sites.domain', 'mobi.comunique-se.com.br');
Config::write('Themes.url', 'http://meu-template-engine.mobi.comunique-se.com.br/themes.json');

Config::write('PushWoosh.debug', false);
