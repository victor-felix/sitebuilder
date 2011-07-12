<?php

try {
    require dirname(__DIR__) . '/config/bootstrap.php';

    Config::write('App.environment', trim(Filesystem::read('config/ENVIRONMENT')));

    require 'config/settings.php';
    require 'config/connections.php';
    require 'config/routes.php';

    echo \lithium\action\Dispatcher::run(new \lithium\action\Request(array(
        'url' => Mapper::here()
    )));
}
catch(Exception $e) {
    Debug::log((string) $e);

    if(Config::read('Debug.showErrors')) {
        echo '<pre>', $e, '</pre>';
    }
    else {
        // @todo do something to prevent white screen of death
    }
}
