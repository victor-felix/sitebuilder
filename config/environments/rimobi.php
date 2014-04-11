<?php

ini_set('error_reporting', 0);
ini_set('display_errors', 'Off');

Config::write('Yaml.cache', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.int-meumobilesite.com');
Config::write('Sites.domain', 'int-meumobilesite.com');

Config::write('Themes.url', 'http://meu-template-engine.int-meumobilesite.com/themes.json');
