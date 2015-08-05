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
			'existing' => 0,
			'created' => 0,
		];


		$shouldCreate = $shouldCreate ?: function($new) { return true; };
		$shouldUpdate = $shouldUpdate ?: function($new) {
			return $new->id();
		};

		$importedItemsIds = array_reduce($items, function($ids, $item)
			use ($shouldCreate, $shouldUpdate, $stats) {
			if ($shouldUpdate($item) && $this->updateItem($item)) {
				$ids[] = $item->id();
				$stats['existing'] += 1;
			} else if ($shouldCreate($item) && $this->createItem($item)) {
				$ids[] = $item->id();
				$stats['created'] += 1;
			}
		}, []);


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
