<?php

// defines the root directory
define('APP_ROOT', dirname(dirname(__FILE__)));
define('LIB_ROOT', dirname(dirname(__FILE__)) . '/meu-site-builder');

// adds the root directory to the include path
set_include_path(APP_ROOT . PATH_SEPARATOR . LIB_ROOT . PATH_SEPARATOR . get_include_path());

require 'config/bootstrap/spaghetti.php';
require 'config/bootstrap/lithium.php';

// includes application's files
require 'app/controllers/app_controller.php';
require 'app/models/app_model.php';
require 'app/models/meu_mobi.php';
