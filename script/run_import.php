#!/usr/bin/php
<?php
//$log = dirname( dirname(__FILE__) ) . "/tmp/import.log";
exec('php ' . dirname(__FILE__) . "/run_works.php import  >> /dev/null  &");
exit;
?>
