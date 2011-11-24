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
    'template' => '/api/{:slug}/items/{:id}/related(.{:type})?',
    'params' => array(
        'action' => 'related',
        'controller' => 'items'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/items/by_category(.{:type})?',
    'params' => array(
        'action' => 'by_category',
        'controller' => 'items'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:category_id}/geo/nearest(.{:type})?',
    'params' => array(
        'action' => 'nearest',
        'controller' => 'geo'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:category_id}/geo/inside(.{:type})?',
    'params' => array(
        'action' => 'inside',
        'controller' => 'geo'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'POST',
    'template' => '/api/{:slug}/items/{:item_id}/images(.{:type})?',
    'params' => array(
        'action' => 'create',
        'controller' => 'images'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/items/{:item_id}/images(.{:type})?',
    'params' => array(
        'action' => 'index',
        'controller' => 'images'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/news/category(.{:type})?',
    'params' => array(
        'action' => 'category',
        'controller' => 'news'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:id}/children(.{:type})?',
    'params' => array(
        'action' => 'children',
        'controller' => 'categories'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}/categories/{:category_id}/search(.{:type})?',
    'params' => array(
        'action' => 'search',
        'controller' => 'items'
    ) + $defaults['params']
)));

Router::connect(new Route(array(
    'method' => 'GET',
    'template' => '/api/{:slug}(.{:type})?',
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

\lithium\net\http\Media::type('csv', null, array(
    'cast' => true,
    'encode' => function($data) {
        if(is_hash($data)) {
            $header = array();
            $row = array();
            foreach($data as $column => $value) {
                if(!is_array($value)) {
                    $header []= $column;
                    $row []= $value;
                }
            }
            $row = '"' . join('","', $row) . '"';
            $header = '"' . join('","', $header) . '"';
            return join(PHP_EOL, array($header, $row));
        }
        else {
            $result = array();
            $header = array_keys($data[0]);
            $result []= '"' . join('","', $header) . '"';
            foreach($data as $row) {
                $r = array();
                foreach($header as $column) {
                    if(!is_array($row[$column])) {
                        $r []= $row[$column];
                    }
                    else {
                        $r []= '';
                    }
                }
                $result []= '"' . join('","', $r) . '"';
            }
            return join(PHP_EOL, $result);
        }
    }
));
