<?php

Config::write('App.environment', trim(Filesystem::read('config/ENVIRONMENT')));
Config::write('App.encoding', 'utf-8');
Config::write('Security.salt', '0b693e040f5c7ffd13d62330d6c8f901');

Config::write('Mailer.transport', 'mail');

require 'config/environments/' . Config::read('App.environment') . '.php';
require 'config/app/segments.php';
require 'config/app/business_items.php';
require 'config/app/resizes.php';

YamlDictionary::path('config/segments');
