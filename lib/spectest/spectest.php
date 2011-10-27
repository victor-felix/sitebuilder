<?php

$dependencies = array('spec', 'spec_runner', 'assertions');

foreach ($dependencies as $dependency) {
    require_once 'lib/spectest/' . $dependency . '.php';
}

unset($dependencies);
