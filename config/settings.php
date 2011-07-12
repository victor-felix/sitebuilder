<?php

if(!Config::read('App.environment')) {
    Config::write('App.environment', trim(Filesystem::read('config/ENVIRONMENT')));
}

Config::write('App.encoding', 'utf-8');
Config::write('Security.salt', '0b693e040f5c7ffd13d62330d6c8f901');
Config::write('Mailer.transport', 'mail');

require 'config/settings.app.php';

$dir = new DirectoryIterator(__DIR__ . '/initializers');
foreach($dir as $file) {
    if($file->isFile()) {
        require $file->getPathname();
    }
}

require 'config/environments/' . Config::read('App.environment') . '.php';
