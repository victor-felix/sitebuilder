<?php
set_time_limit(20 * 60); //20 minutes
require dirname(dirname(__DIR__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'app/models/sites.php';

$_ = array_shift($argv);

foreach ($argv as $site_Id) {
	$site = new Sites();
	if ($site->delete($site_Id)) {
		echo "Site $site_Id successfully removed\n";
	} else {
		echo "Can't remove site $site_Id\n";
	}
}
