<?php

ini_set('error_reporting', 0);
ini_set('display_errors', 'Off');

Config::write('Yaml.cache', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.meumobi.com');
Config::write('Sites.domain', 'meumobi.com');

Config::write('Themes.url', 'http://meu-cloud-db.meumobi.com/configs.json');
Config::write('TemplateEngine.url', 'http://meu-template-engine.meumobi.com');
