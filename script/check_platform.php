<?php

$checks = array(
    'php_version' => function() {
        $result = true;
        $result = $result && PHP_MAJOR_VERSION == 5;
        $result = $result && PHP_MINOR_VERSION == 3;
        $result = $result && PHP_RELEASE_VERSION > 1;
        return array($result, '> 5.3.1', phpversion());
    },
    'short_open_tag' => function() {
        $ini = ini_get('short_open_tag');
        $result = $ini != 'On';
        return array($result, 'Off', $ini);
    },
    'magic_quotes_gpc' => function() {
        $ini = ini_get('magic_quotes_gpc');
        $result = $ini != 'On';
        return array($result, 'Off', $ini);
    },
    'magic_quotes_runtime' => function() {
        $ini = ini_get('magic_quotes_runtime');
        $result = $ini != 'On';
        return array($result, 'Off', $ini);
    },
    'pdo' => function() {
        $result = phpversion('pdo');
        return array($result, 'true', $result ? 'true' : 'false');
    },
    'mongo' => function() {
        $version = phpversion('mongo');
        $result = $version == '1.2.6';
        return array($result, '1.2.6', $version);
    },
    'imagick' => function() {
        $version = phpversion('imagick');
        $result = preg_match('/^3\.0/', $version);
        return array($result, '> 3.0.0', $version);
    },
    'gd' => function() {
        $result = function_exists('getimagesize');
        return array($result, 'true', $result ? 'true' : 'false');
    },
    'curl' => function() {
        $result = function_exists('curl_init');
        return array($result, 'true', $result ? 'true' : 'false');
    },
);

$errors = array();

foreach($checks as $name => $check) {
    list($result, $expected, $got) = $check();
    if($result) {
        echo chr(27) . "[1;32m." . chr(27) . "[0m";
    }
    else {
        $errors []= array($name, $expected, $got);
        echo chr(27) . "[1;31mF" . chr(27) . "[0m";
    }
}

if(empty($errors)) {
    echo PHP_EOL . chr(27) . '[1;32mPLATFORM OK!' . chr(27) . "[0m" . PHP_EOL;
    exit(0);
}
else {
    echo PHP_EOL . chr(27) . '[1;31mPLATFORM NOK!' . chr(27) . "[0m" . PHP_EOL;
    echo 'Errors:' . PHP_EOL;
    foreach($errors as $error) {
        echo $error[0] . ':';
        echo ' expected ' . chr(27) . '[1;32m' . $error[1] . chr(27) . '[0m, ';
        echo 'got: ' . chr(27) . '[1;31m' . $error[2] . chr(27) . '[0m' . PHP_EOL;
    }
    exit(1);
}
