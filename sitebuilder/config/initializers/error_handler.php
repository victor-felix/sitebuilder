<?php

use lithium\action\Response;
use lithium\core\ErrorHandler;
use meumobi\sitebuilder\Logger;

ErrorHandler::apply(
	['lithium\action\Dispatcher', 'run'],
	['type' => [
		'app\models\sites\MissingSiteException',
		'app\models\RecordNotFoundException',
		'app\controllers\api\UnAuthorizedException',
		'app\controllers\api\ForbiddenException',
		'app\controllers\api\InvalidArgumentException',
		'meumobi\sitebuilder\repositories\RecordNotFoundException',
		'Exception',
	]],
	function($exception, $params) {
		$e = $exception['exception'];
		Logger::error('sitebuilder', sprintf('uncaught exception %s: "%s" at %s line %s',
			get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()),
			['exception' => $e]);

		$response = new Response([
			'status' => property_exists($e, 'status')
				? $e->status
				: 500,
			'body' => json_encode(['error' => $exception['message']])
		]);

		echo $response->render();
	}
);

$logger = Logger::logger(Config::read('Log.level'));
Monolog\ErrorHandler::register($logger);
