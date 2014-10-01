<?php

$checks = array(
	'php_version' => function() {
		$result = true;
		$result = $result && PHP_MAJOR_VERSION == 5;
		$result = $result && PHP_MINOR_VERSION > 3;
		return array($result, '> 5.3', phpversion());
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
		$result = preg_match('/^1\.[2-6].(\d)+/', $version);
		return array($result, '> 1.2.10', $version);
	},
	'imagick' => function() {
		$version = phpversion('imagick');
		$result = preg_match('/^3\.[0-9]/', $version);
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
	'max_execution_time' => function() {
		$result = ini_get('max_execution_time');
		return array($result == '0', '0', $result);
	},
	'config_files' => function() {
		$files = array('config/ENVIRONMENT', 'config/connections.php');
		$found = array_filter($files, function($file) {
			return file_exists(__DIR__ . '/../../' . $file);
		});
		$result = count($files) == count($found);
		return array($result, implode(', ', $files), implode(', ', $found));
	},
	'rwx_permissions' => function() {
		$files = array('tmp');
		$ok_files = array_filter($files, function($file) {
			return (fileperms(__DIR__ . '/../../' . $file) & 0777) == 0777;
		});
		$result = count($files) == count($ok_files);
		return array($result, implode(', ', $files), implode(', ', $ok_files));
	}
);

$errors = array();

foreach ($checks as $name => $check) {
	list($result, $expected, $got) = $check();
	if ($result) {
		echo chr(27) . "[1;32m." . chr(27) . "[0m";
	} else {
		$errors []= array($name, $expected, $got);
		echo chr(27) . "[1;31mF" . chr(27) . "[0m";
	}
}

if (empty($errors)) {
	echo PHP_EOL . chr(27) . '[1;32mPLATFORM OK!' . chr(27) . "[0m" . PHP_EOL;
	exit(0);
} else {
	echo PHP_EOL . chr(27) . '[1;31mPLATFORM NOK!' . chr(27) . "[0m" . PHP_EOL;
	echo 'Errors:' . PHP_EOL;
	foreach ($errors as $error) {
		echo $error[0] . ':';
		echo ' expected ' . chr(27) . '[1;32m' . $error[1] . chr(27) . '[0m, ';
		echo 'got: ' . chr(27) . '[1;31m' . $error[2] . chr(27) . '[0m' . PHP_EOL;
	}
	exit(1);
}
