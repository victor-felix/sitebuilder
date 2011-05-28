<?php

if(!Config::read('App.environment')) {
    Config::write('App.environment', trim(Filesystem::read('config/ENVIRONMENT')));
}

Config::write('App.encoding', 'utf-8');
Config::write('Security.salt', '0b693e040f5c7ffd13d62330d6c8f901');

Config::write('Mailer.transport', 'mail');

require 'config/environments/' . Config::read('App.environment') . '.php';

Config::write('Sites.blacklist', array('feedback', 'blog', 'restaurant', 'support', 'dropbox', 'analytics', 'wiki', 'events', 'corporate'));

require 'config/app/segments.php';
require 'config/app/resizes.php';

I18n::locale('en');
YamlDictionary::path('config/segments');

date_default_timezone_set('UTC');
