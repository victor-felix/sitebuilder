<?php

use app\models\Extensions;
use lithium\data\Connections;
use meumobi\sitebuilder\services\BulkImportItems;

class SetImportModeOnEventsExtensions
{
	public static function migrate($connection)
	{
		$events = Extensions::update([
			'import_mode' => BulkImportItems::INCLUSIVE_IMPORT], [
			'extension' => ['event-feed'],
		]);
	}
}
