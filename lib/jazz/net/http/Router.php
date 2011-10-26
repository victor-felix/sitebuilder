<?php

namespace jazz\net\http;

class Router extends \lithium\net\http\Router {
    protected static $_classes = array(
        'route' => 'jazz\net\http\Route'
    );

    protected static $_actions = array(
        'index' => array(
            'method' => 'GET',
            'path' => '/{:controller}'
        ),
        'new' => array(
            'method' => 'GET',
            'path' => '/{:controller}/new'
        ),
        'create' => array(
            'method' => 'POST',
            'path' => '/{:controller}'
        ),
        'show' => array(
            'method' => 'GET',
            'path' => '/{:controller}/{:id}'
        ),
        'edit' => array(
            'method' => 'GET',
            'path' => '/{:controller}/{:id}/edit'
        ),
        'update' => array(
            'method' => 'PUT',
            'path' => '/{:controller}/{:id}'
        ),
        'destroy' => array(
            'method' => 'DELETE',
            'path' => '/{:controller}/{:id}'
        ),
    );

    public static function resources($name, $defaults) {
        $defaults += array(
            'scope' => '',
            'only' => array_keys(static::$_actions),
            'params' => array()
        );

        foreach((array) $defaults['only'] as $action) {
            $params = static::$_actions[$action] + $defaults;

            $params['params']['action'] = $action;
            $params['params']['controller'] = $name;

            $params['template'] = \lithium\util\String::insert(
                $params['scope'] . $params['path'],
                array(
                    'controller' => $name
                )
            );

            static::connect(new Route($params));
        }
    }
}
