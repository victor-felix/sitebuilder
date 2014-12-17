<?php
require dirname(__DIR__) . '/config/cli.php';
$_ = array_shift($argv);
$Categories = Model::load('Categories');
foreach ($argv as $id) {
	try {
		$Categories->delete($id);
	} catch (Exception $e) {
		echo $e;
	}
}
