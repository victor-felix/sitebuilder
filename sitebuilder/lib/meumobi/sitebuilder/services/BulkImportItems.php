<?php
namespace meumobi\sitebuilder\services;

use app\models\Items;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\services\CreateItem;
use meumobi\sitebuilder\services\UpdateItem;
use meumobi\sitebuilder\validators\ItemsPersistenceValidator;
use meumobi\sitebuilder\validators\ParamsValidator;

class BulkImportItems
{
	const INCLUSIVE_IMPORT = 'inclusive';
	const EXCLUSIVE_IMPORT = 'exclusive';

	public function perform($params)
	{
		list($category, $items, $mode, $shouldCreate, $shouldUpdate, $sendPush) = ParamsValidator::validate($params, [
			'category',
			'items',
			'mode',
			'shouldCreate',
			'shouldUpdate',
			'sendPush',
		]);

		$stats = [
			'existing_items' => 0,
			'created_items' => 0,
		];

		$importedItemsIds = [];

		$shouldCreate = $shouldCreate ?: function($item) {
			return !$item->id();
		};

		$shouldUpdate = $shouldUpdate ?: function($item) {
			return $item->id();
		};

		foreach ($items as $item) {
			if ($shouldUpdate($item) && $this->updateItem($item)) {
				$stats['existing_items'] += 1;
			} else if ($shouldCreate($item) && $this->createItem($item, $sendPush)) {
				$stats['created_items'] += 1;
			}

			$importedItemsIds[] = $item->id();
		}

		if ($mode == self::EXCLUSIVE_IMPORT) {
			$stats['removed_items'] = $this->removeItems($importedItemsIds, $category);
		}

		return $stats;
	}

	protected function createItem($item, $sendPush)
	{
		$service = new CreateItem();

		return $service->create($item, ['sendPush' => $sendPush]);
	}

	protected function updateItem($item)
	{
		$service = new UpdateItem();

		return $service->update($item);
	}

	protected function removeItems($exceptIds, $category)
	{
		// NOTE: this is the slower way, but is how the Categories::removeItems
		// do, since a bulk remove throws fatal error
		$items = Items::find('all', [
			'conditions' => [
				'_id' => ['$nin' => $exceptIds],
				'parent_id' => $category->id,
			]
		]);

		foreach ($items as $item) {
			Items::remove(['_id' => $item->id()]);
		}

		return count($items);
	}
}
