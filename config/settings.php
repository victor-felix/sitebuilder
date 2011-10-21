<?php

if(!Config::read('App.environment')) {
    Config::write('App.environment', trim(Filesystem::read(__DIR__ . '/ENVIRONMENT')));
}

Config::write('App.encoding', 'utf-8');
Config::write('Security.salt', 'please change this');
Config::write('Mailer.transport', 'mail');

require 'config/settings.app.php';

$dir = new DirectoryIterator(__DIR__ . '/initializers');
foreach($dir as $file) {
    if($file->isFile()) {
        require $file->getPathname();
    }
}

require 'config/environments/' . Config::read('App.environment') . '.php';
