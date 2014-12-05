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
	'method' => 'POST',
	'template' => '/api/{:slug}/mail',
	'params' => array(
		'action' => 'index',
		'controller' => 'mail',
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'POST',
	'template' => '/api/{:slug}/visitors',
	'params' => array(
		'controller' => 'visitors',
		'action' => 'update',
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'POST',
	'template' => '/api/{:slug}/visitors/login',
	'params' => array(
		'controller' => 'visitors',
		'action' => 'login',
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'POST',
	'template' => '/api/{:slug}/visitors/devices',
	'params' => array(
		'controller' => 'visitors',
		'action' => 'addDevice',
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/export/{:category_id}',
	'params' => array(
		'action' => 'export',
		'controller' => 'export',
		'type' => 'csv'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/theme',
	'params' => array(
		'action' => 'theme',
		'controller' => 'sites'
	) + $defaults['params']
)));

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
	'template' => '/api/{:slug}/items/latest',
	'params' => array(
		'action' => 'latest',
		'controller' => 'items'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/items/latest',
	'params' => array(
		'action' => 'latest',
		'controller' => 'items'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/items/search',
	'params' => array(
		'controller' => 'items',
		'action' => 'search',
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/categories/{:category_id}/items',
	'params' => array(
		'action' => 'index',
		'controller' => 'items'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/categories/{:category_id}/items.rss',
	'params' => array(
		'action' => 'index_rss',
		'controller' => 'items',
		'type' => 'rss',
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/categories/{:category_id}/promotions',
	'params' => array(
		'action' => 'promotions',
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
	'template' => '/api/{:slug}/news',
	'params' => array(
		'action' => 'news',
		'controller' => 'items'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/news/category',
	'params' => array(
		'action' => 'showNewsCategory',
		'controller' => 'categories'
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
		'controller' => 'categories'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/{:slug}/performance',
	'params' => array(
		'action' => 'performance',
		'controller' => 'sites'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/skins',
	'params' => array(
		'action' => 'index',
		'controller' => 'skins'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'GET',
	'template' => '/api/skins/{:id}',
	'params' => array(
		'action' => 'show',
		'controller' => 'skins'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'PUT',
	'template' => '/api/skins/{:id}',
	'params' => array(
		'action' => 'update',
		'controller' => 'skins'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'DELETE',
	'template' => '/api/skins/{:id}',
	'params' => array(
		'action' => 'destroy',
		'controller' => 'skins'
	) + $defaults['params']
)));

Router::connect(new Route(array(
	'method' => 'POST',
	'template' => '/api/skins',
	'params' => array(
		'action' => 'create',
		'controller' => 'skins'
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
Router::resources('extensions', $defaults);
Router::resources('items', $defaults);
Router::resources('images', $defaults);

Router::connect(new Route(array(
	'template' => '/{:controller}/{:action}/{:args}.{:extension}',
	'params' => array(
		'controller' => 'home',
		'extension' => 'htm'
	)
)));

$locales = I18n::availableLanguages();

Router::connect(new Route(array(
	'template' => '(/{:locale:' . join('|', $locales) . '})?/{:controller}/{:action}/{:args}',
	'params' => array(
		'controller' => 'home',
		'extension' => 'htm'
	)
)));

Dispatcher::applyFilter('run', function($self, $params, $chain) {
	if ($route = Router::parse($params['request'])) {
		if ($route->get('params:api')) {
			return $chain->next($self, $params, $chain);
		} else {
			$class = Inflector::camelize($route->get('params:controller')) . 'Controller';
			$controller = Controller::load($class, true);
			$controller->request = $params['request'];
			echo $controller->callAction($params['request']);
		}
	} else {
		die();
	}
});

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
	$controller = $chain->next($self, $params, $chain);

	if ($controller->beforeFilter() === false) {
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

\lithium\net\http\Media::type('csv', 'text/csv', array(
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
					if(is_string($row[$column])) {
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

\lithium\net\http\Media::type('rss', 'application/rss+xml', array(
	'encode' => function($data) {
		$view = new View();
		return $view->render('business_items/feed.rss', $data, false);
	}
));
