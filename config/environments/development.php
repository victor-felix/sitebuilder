<?php

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');

Config::write('Mail.preventSending', true);
Config::write('Debug.showErrors', true);

Config::write('Api.ignoreAuth', true);

Config::write('Themes.url', 'http://meu-cloud-db.int-meumobi.com/configs.json');
Config::write('Themes.ignoreTag', true);
Config::write('SiteManager.url', 'http://meu-site-manager.meumobilesite.com');
Config::write('TemplateEngine.url', 'http://meu-template-engine.int-meumobi.com');
