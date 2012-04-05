#!/usr/bin/php
<?php
$log = dirname( dirname(__FILE__) ) . "/tmp/geocode.log";
exec('php ' . dirname(__FILE__) . "/run_works.php geocode >>  $log  &");
exit;
?>
