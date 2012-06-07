#!/usr/bin/php
<?php
chdir(__DIR__);
require realpath('../../config/bootstrap.php');
require_once 'lib/utils/Work.php';
utils\Work::initCronJobs(null, false);