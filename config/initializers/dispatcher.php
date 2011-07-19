<?php

use lithium\action\Dispatcher;
use lithium\net\http\Router;

Router::connect('/api/{:slug:[^\/]+}/{:controller}/{:action}/{:args}', array(
    'type' => 'json',
    'api' => true
));

Dispatcher::config(array(
    'rules' => array(
        'api' => array(
            'controller' => '\app\controllers\api\{:controller}Controller'
        )
    )
));

Dispatcher::applyFilter('run', function($self, $params, $chain) {
    if($route = Router::parse($params['request'])) {
        return $chain->next($self, $params, $chain);
    }
    else {
        echo \Dispatcher::dispatch(null, $params['request']);
    }
});

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
    $controller = $chain->next($self, $params, $chain);

    $controller->beforeFilter();

    return $controller;
});
