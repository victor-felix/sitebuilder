<?php

require dirname(__DIR__) . '/config/cli.php';

require 'lib/utils/Worker.php';

array_shift($argv);
$type = array_shift($argv);
$worker = new utils\Worker($type);
$worker->run();
