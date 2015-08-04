<?php
namespace meumobi\sitebuilder\services;

class BulkImportItems
{
	public function perform($params)
	{
		list($category, $items, $mode, $shouldCreate, $shouldUpdate) =  ParamsValidator::validate($params, [
			'category',
			'items',
			'mode',
			'shouldCreate',
			'shouldUpdate',
		]);

		$shouldCreate = $shouldCreate ?: function($new) { return true; };
		$shouldUpdate = $shouldUpdate ?: function($new) {
			return $new->id();
		};

		$importedItemsIds = array_reduce($items, function($ids, $item) {
			if ($shouldUpdate($item) && $this->updateItem($item)) {
				$ids[] = $item->id();
			} else if ($shouldCreate($item) && $this->createItem($item)) {
				$ids[] = $item->id();
			}
		}, []);


		if ($mode == EXCLUSIVE) {
			Items::remove(['_id' => ['$nin' => $importedItemsIds]]);
		}
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
