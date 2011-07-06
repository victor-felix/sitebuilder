<?php

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');

Config::write('Mail.preventSending', true);
Config::write('Debug.showErrors', true);

Config::write('Themes.url', 'http://meu-template-engine.int-meumobi.com/api/index');
