<?php

define('LIB_ROOT', dirname(__DIR__));
define('APP_ROOT', dirname(LIB_ROOT));
define('ERROR_LOG', dirname(dirname(__DIR__)) . '/log/php-' . date('Y-m-d') . '.log');

set_include_path(APP_ROOT . PATH_SEPARATOR .
	LIB_ROOT . PATH_SEPARATOR . get_include_path());

ini_set('error_log', ERROR_LOG);

require_once 'vendor/autoload.php';

require_once 'lib/htmlpurifier/HTMLPurifier/Bootstrap.php';
spl_autoload_register(array('HTMLPurifier_Bootstrap', 'autoload'));

require_once 'config/bootstrap/lithium.php';
require_once 'config/bootstrap/spaghetti.php';
require_once 'config/bootstrap/initializers.php';

require_once 'config/settings.php';
require_once 'config/connections.php';

require_once 'app/models/app_model.php';
require_once 'app/controllers/app_controller.php';
require_once 'app/models/meu_mobi.php';


return function($segment) {
	require_once 'segments/' . $segment . '/config.php';

	YamlDictionary::dictionary('strings');
	YamlDictionary::path(APP_ROOT . '/segments/' . $segment);

	try {
		echo \lithium\action\Dispatcher::run(new \lithium\action\Request(array(
			'url' => Mapper::here()
		)));
//	} catch (SpaghettiException $e) {
//		echo $e->toString();
	} catch (Exception $e) {
		Debug::log((string) $e);

//		if (Config::read('Debug.showErrors')) {
			echo '<pre>', $e, '</pre>';
	//	}
	}
};
