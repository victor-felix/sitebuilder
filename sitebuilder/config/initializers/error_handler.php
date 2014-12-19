<?php

use lithium\core\ErrorHandler;

ErrorHandler::apply(array('lithium\action\Dispatcher', 'run'),
	array('type' => array(
		'app\models\sites\MissingSiteException',
		'app\models\RecordNotFoundException',
		'app\controllers\api\UnAuthorizedException',
		'app\controllers\api\ForbiddenException',
		'app\controllers\api\InvalidArgumentException',
		'meumobi\sitebuilder\repositories\RecordNotFoundException'
	)),
	function($exception, $params) {
		$response = new \lithium\action\Response(array(
			'status' => $exception['exception']->status,
			'body' => json_encode(array('error' => $exception['message']))
		));
		echo $response->render();
	}
);
