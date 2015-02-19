<?php

use meumobi\sitebuilder\services\PublishItems;

require dirname(__DIR__) . '/config/cli.php';

$service = new PublishItems();
$service->call();
