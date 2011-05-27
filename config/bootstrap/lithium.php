<?php

require 'lib/lithium/core/Libraries.php';

use lithium\core\Libraries;

Libraries::add('lithium');
Libraries::add('app', array(
    'path' => APP_ROOT . '/app'
));
Libraries::add('activerecord', array(
    'bootstrap' => false, 
    'path' => APP_ROOT . '/lib/activerecord',
    'prefix' => 'ActiveRecord'
));
