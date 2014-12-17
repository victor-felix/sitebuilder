<?php

define('LITHIUM_LIBRARY_PATH', 'lib');
define('LITHIUM_APP_PATH', 'app');

require 'lib/lithium/core/Libraries.php';

use lithium\core\Libraries;

Libraries::add('lithium');
Libraries::add('jazz', array(
	'path' => LIB_ROOT . '/lib/jazz'
));
Libraries::add('app', array(
	'path' => LIB_ROOT . '/app',
	'transform' => function($class, $config) {
		return str_replace('\\', '/', $class) . '.php';
	}
));
Libraries::add('meumobi', array(
	'path' => LIB_ROOT . '/lib/meumobi'
));
