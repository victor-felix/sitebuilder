<?php

define('LIB_ROOT', dirname(__DIR__));
define('APP_ROOT', dirname(LIB_ROOT));

set_include_path(APP_ROOT . PATH_SEPARATOR .
	LIB_ROOT . PATH_SEPARATOR . get_include_path());

require 'config/bootstrap/lithium.php';
require 'config/bootstrap/spaghetti.php';
require 'config/bootstrap/initializers.php';

require 'config/settings.php';
require 'config/connections.php';
require 'config/routes.php';

require 'app/models/app_model.php';
require 'app/controllers/app_controller.php';
require 'app/models/meu_mobi.php';

return function($segment) {
	require 'segments/' . $segment . '/config.php';

	YamlDictionary::dictionary('strings');
	YamlDictionary::path(APP_ROOT . '/segments/' . $segment);

	try {
		echo \lithium\action\Dispatcher::run(new \lithium\action\Request(array(
			'url' => Mapper::here()
		)));
	} catch (Exception $e) {
		Debug::log((string) $e);

		if (Config::read('Debug.showErrors')) {
			echo '<pre>', $e, '</pre>';
		}
	}
};
