<?php

require dirname(dirname(__FILE__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'lib/spectest/spectest.php';
require 'test/spec_helper.php';

$specs_path = Filesystem::path('test/unit');

$runner = new SpecRunner();
$runner->require_all($specs_path);
$runner->setDescriptiveOutput(false);
$runner->run();
