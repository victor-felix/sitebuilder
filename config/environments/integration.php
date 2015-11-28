<?php

Config::write('Log.level', Psr\Log\LogLevel::NOTICE);

Config::write('Mail.preventSending', false);

Config::write('Api.ignoreAuth', true);
Config::write('Preview.url', 'http://placeholder.int.meumobi.com');
Config::write('Sites.domain', 'int.meumobi.com');

Config::write('Themes.url', 'http://meu-template-engine.int.meumobi.com/themes.json');

Config::write('PushWoosh.debug', true);
