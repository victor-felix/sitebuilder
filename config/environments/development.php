<?php

ini_set('display_errors', 'On');

Config::write('Log.level', Psr\Log\LogLevel::DEBUG);

Config::write('Mail.preventSending', true);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.int-meumobi.com');
Config::write('Sites.domain', 'int-meumobi.com');

Config::write('Themes.url', LIB_ROOT . '/config/themes.json');

Config::write('PushWoosh.debug', true);
