<?php
namespace meumobi\sitebuilder\services;

use app\models\Items;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ItemsPersistenceValidator;
use meumobi\sitebuilder\validators\ParamsValidator;

class BulkImportItems
{
	const INCLUSIVE_IMPORT = 'imclusive';
	const EXCLUSIVE_IMPORT = 'exclusive';

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

		if ($mode == self::EXCLUSIVE_IMPORT) {
			//NOTE this is the slower way, but is how the Categories::removeItems do, since a bulk remove throws fatal error
			$items = Items::find('all', [
				'conditions' => [
					'_id' => ['$nin' => $importedItemsIds]
				]
			]);

			foreach ($items as $item) {
				Items::remove(['_id' => $item->id()]);
			}
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
		//TODO use ItemUpdate service when it becomes available
		$validator = new ItemsPersistenceValidator();
		$validationResult = $validator->validate($item);
		return $validationResult->isValid() && $item->save();
	}
}
