#!/usr/bin/php
<?php
require_once dirname( dirname(__FILE__) ) . '/lib/utils/Worker.php';

array_shift($argv);
$type = array_shift($argv);
$worker = new utils\Worker($type);
$worker->run();
