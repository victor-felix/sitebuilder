<?php

use lithium\action\Dispatcher;
use jazz\net\http\Router;
use jazz\net\http\Route;

Dispatcher::config(array(
    'rules' => array(
        'api' => array(
            'controller' => '\app\controllers\api\{:controller}Controller'
        )
    ),
    'classes' => array(
        'router' => 'jazz\net\http\Router'
    )
));

$defaults = array(
    'scope' => '/api/{:slug}',
    'params' => array(
        'api' => true,
        'type' => 'json'
    )
);

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/items/{:id}/related',
    'params' => array(
        'action' => 'related',
        'controller' => 'items'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/items/by_category',
    'params' => array(
        'action' => 'by_category',
        'controller' => 'items'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:category_id}/geo/nearest',
    'params' => array(
        'action' => 'nearest',
        'controller' => 'geo'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:category_id}/geo/inside',
    'params' => array(
        'action' => 'inside',
        'controller' => 'geo'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'POST',
    'template' => '/api/{:slug}/items/{:item_id}/images',
    'params' => array(
        'action' => 'create',
        'controller' => 'images'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/items/{:item_id}/images',
    'params' => array(
        'action' => 'index',
        'controller' => 'images'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/news/category',
    'params' => array(
        'action' => 'category',
        'controller' => 'news'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:id}/children',
    'params' => array(
        'action' => 'children',
        'controller' => 'categories'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:category_id}/search',
    'params' => array(
        'action' => 'search',
        'controller' => 'items'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}',
    'params' => array(
        'action' => 'show',
        'controller' => 'sites'
    ) + $defaults['params']
)));

Router::resources('categories', $defaults);
Router::resources('items', $defaults);
Router::resources('news', array('only' => 'index') + $defaults);
Router::resources('images', $defaults);
Router::resources('sites', $defaults);

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

    if($controller->beforeFilter() === false) {
        echo $controller->response;
        die();
    };

    return $controller;
});

\lithium\net\http\Media::type('form', 'application/x-www-form-urlencoded', array(
    'cast' => true,
    'encode' => 'http_build_query',
    'decode' => function($content) {
        parse_str($content, $output);
        return $output;
    }
));
