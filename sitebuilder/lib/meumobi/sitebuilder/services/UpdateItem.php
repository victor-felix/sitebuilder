<?php

namespace meumobi\sitebuilder\services;

use Inflector;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\services\ProcessRemoteMedia;
use meumobi\sitebuilder\validators\ItemsPersistenceValidator;
use meumobi\sitebuilder\validators\ParamsValidator;

class UpdateItem
{
	public function update($item, $data = null, $options = [])
	{
		if ($data) {
			$blacklist = ['medias'];
			$keys = array_keys($data);
			$allowedKeys = array_diff($keys, $blacklist);

			foreach ($allowedKeys as $k) {
				$item->set([ $k => $data[$k] ]);
			}

			$this->updateMedia($item, $data);
		}

		$validator = new ItemsPersistenceValidator();
		$validationResult = $validator->validate($item);

		if ($validationResult->isValid()) {
			$item->save();

			Logger::info('items', 'item updated', [
				'item_id' => $item->id(),
				'site_id' => $item->site_id,
				'category_id' => $item->parent_id,
			]);

			$this->processRemoteMedia($item);

			$updated = true;
		} else {
			Logger::info('items', 'item cannot be updated', [
				'item_id' => $item->id(),
				'site_id' => $item->site_id,
				'category_id' => $item->parent_id,
				'errors' => $validationResult->errors(),
			]);

			$updated = false;
		}

		return [$updated, $validationResult->errors()];
	}

	protected function updateMedia($item, $data)
	{
		if (isset($data['medias'])) {
			$media = [];

			foreach ($data['medias'] as $medium) {
				$finder = function ($i) use ($medium) {
					return $i['url'] == $medium['url'];
				};

				if ($m = find($item->medias->to('array'), $finder)) {
					$media []= array_merge($m, $medium);
				} else {
					$media []= $medium;
				}
			}
		}

		unset($item['medias']);
		$item->set([ 'medias' => $media ]);
	}

	protected function processRemoteMedia($item)
	{
		$service = new ProcessRemoteMedia;
		$service->schedule($item);
	}

}

function find($collection, $fn)
{
	foreach ($collection as $item) {
		if ($fn($item)) return $item;
	}
}
