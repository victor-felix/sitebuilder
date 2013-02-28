<?php

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');

Config::write('Mail.preventSending', true);
Config::write('Debug.showErrors', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.meumobi.com');
Config::write('Sites.domain', 'int-meumobi.com');

Config::write('Themes.url', 'http://meu-cloud-db.int-meumobilesite.com/configs.json');
Config::write('SiteManager.url', 'http://meu-site-manager.int-meumobilesite.com');
Config::write('TemplateEngine.url', 'http://meu-template-engine.int-meumobi.com');
