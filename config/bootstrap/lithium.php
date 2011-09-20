<?php

require 'lib/lithium/core/Libraries.php';

use lithium\core\Libraries;

Libraries::add('lithium');
Libraries::add('jazz');
Libraries::add('app', array(
    'path' => APP_ROOT . '/app'
));
