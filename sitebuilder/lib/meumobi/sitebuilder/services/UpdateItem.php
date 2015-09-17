<?php

namespace meumobi\sitebuilder\services;

use Inflector;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\validators\ItemsPersistenceValidator;
use meumobi\sitebuilder\validators\ParamsValidator;

class UpdateItem
{
	public function update($item, $options = [])
	{
		$validator = new ItemsPersistenceValidator();
		$validationResult = $validator->validate($item);

		if ($validationResult->isValid()) {
			$item->save();

			Logger::info('items', 'item updated', [
				'item_id' => $item->id(),
				'site_id' => $item->site_id,
				'category_id' => $item->parent_id,
			]);

			$updated = true;
		} else {
			Logger::info('items', 'item cannot be updated', [
				'id' => $item->id(),
				'site_id' => $item->site_id,
				'category_id' => $item->parent_id,
				'errors' => $validationResult->errors(),
			]);

			$updated = false;
		}

		return [$updated, $validationResult->errors()];
	}
}
