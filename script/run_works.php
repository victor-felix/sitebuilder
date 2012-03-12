#!/usr/bin/php
<?php
require_once dirname( dirname(__FILE__) ) . '/lib/utils/Daemonize.php';

array_shift($argv);
$type = array_shift($argv);
$delay = array_shift($argv);
$daemon = new Daemonize($type, $delay);
$daemon->run();