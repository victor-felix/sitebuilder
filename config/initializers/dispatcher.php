<?php

use lithium\action\Dispatcher;
use lithium\net\http\Router;

Router::connect('/api/{:slug:\w+}/{:controller}/{:action}/{:args}', array(
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
        echo \Dispatcher::dispatch();
    }
});

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
    $controller = $chain->next($self, $params, $chain);

    $slug = $params['request']->params['slug'];
    $controller->site(Model::load('Sites')->firstBySlug($slug));

    return $controller;
});
