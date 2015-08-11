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
		$shouldCreate = $shouldCreate ?: function($new) { return true; };
		$shouldUpdate = $shouldUpdate ?: function($new) {
			return $new->id();
		};

		foreach ($items as $item) {
			if ($shouldUpdate($item) && $this->updateItem($item)) {
				$importedItemsIds[] = $item->id();
				$stats['existing_items'] += 1;
			} else if ($shouldCreate($item) && $this->createItem($item, $sendPush)) {
				$importedItemsIds[] = $item->id();
				$stats['created_items'] += 1;
			}
		}

		if ($mode == self::EXCLUSIVE_IMPORT) {
			$stats['excluded_items'] = $this->excludeItems($importedItemsIds, $category);
		}

		return $stats;
	}

	protected function createItem($item, $sendPush)
	{
		$service = new ItemCreation();

		return $service->create($item, ['sendPush' => $sendPush]);
	}

	protected function updateItem($item)
	{
		//TODO use ItemUpdate service when it becomes available
		return true;
	}

	protected function excludeItems($exceptIds, $category)
	{
		//NOTE this is the slower way, but is how the Categories::removeItems do, since a bulk remove throws fatal error
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
