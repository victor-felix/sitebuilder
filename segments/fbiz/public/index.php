<?php

$dispatcher = require dirname(dirname(dirname(__DIR__))) . '/sitebuilder/config/bootstrap.php';
$segment = basename(dirname(__DIR__));
$dispatcher($segment);
