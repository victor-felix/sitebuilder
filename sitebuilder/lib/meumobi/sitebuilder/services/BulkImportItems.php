<?php
namespace meumobi\sitebuilder\services;

use app\models\Items;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class BulkImportItems
{
	const INCLUSIVE = 0;
	const EXCLUSIVE = 1;

	public function perform($params)
	{
		list($category, $items, $mode, $shouldCreate, $shouldUpdate) =  ParamsValidator::validate($params, [
			'category',
			'items',
			'mode',
			'shouldCreate',
			'shouldUpdate',
		]);

		$stats = [
			'existing_items' => 0,
			'created_items' => 0,
		];

		$importedItemsIds = [];
		$shouldCreate = $shouldCreate ?: function($new) { return true; };
		$shouldUpdate = $shouldUpdate ?: function($new) {
			return $new->id();
		};


		foreach ($items as $item) {
			if ($shouldUpdate($item) && $this->updateItem($item)) {
				$importedItemsIds[] = $item->id();
				$stats['existing_items'] += 1;
			} else if ($shouldCreate($item) && $this->createItem($item)) {
				$importedItemsIds[] = $item->id();
				$stats['created_items'] += 1;
			}
		}


		if ($mode == static::EXCLUSIVE) {
			Items::remove(['_id' => ['$nin' => $importedItemsIds]]);
		}

		return $stats;
	}

	protected function createItem($item)
	{
		$service = new ItemCreation();

		return $service->create($item);
	}

	protected function updateItem($item)
	{
		//TODO
	}
}
