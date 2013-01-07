<?php

use lithium\core\ErrorHandler;

ErrorHandler::apply(array('lithium\action\Dispatcher', 'run'),
	array('type' => array(
		'app\models\sites\MissingSiteException',
		'app\controllers\api\NotAuthenticatedException',
	)),
	function($exception, $params) {
		echo json_encode(array('error' => $exception['message']));
	}
);
