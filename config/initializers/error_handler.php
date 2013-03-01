<?php

use lithium\core\ErrorHandler;

ErrorHandler::apply(array('lithium\action\Dispatcher', 'run'),
	array('type' => array(
		'app\models\sites\MissingSiteException',
		'app\models\items\ItemNotFoundException',
		'app\controllers\api\NotAuthenticatedException',
		'app\controllers\api\InvalidArgumentException',
	)),
	function($exception, $params) {
		$response = new \lithium\action\Response(array(
			'status' => $exception['exception']->status,
			'body' => json_encode(array('error' => $exception['message']))
		));
		echo $response->render();
	}
);
