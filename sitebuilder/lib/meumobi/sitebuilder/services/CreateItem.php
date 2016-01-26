<?php

namespace meumobi\sitebuilder\services;

use Inflector;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\services\ProcessRemoteMedia;
use meumobi\sitebuilder\validators\ItemsPersistenceValidator;
use meumobi\sitebuilder\validators\ParamsValidator;

class CreateItem
{
	public function build($data)
	{
		$category = Model::load('Categories')->firstById($data['parent_id']);
		$classname = '\app\models\items\\' . Inflector::camelize($category->type);
		$item = $classname::create();
		$item->set($data);
		$item->type = $category->type;
		return $item;
	}

	public function create($item, $options = [])
	{
		$validator = new ItemsPersistenceValidator();
		$validationResult = $validator->validate($item);
		list($sendPush) = ParamsValidator::validate($options, ['sendPush']);

		if ($validationResult->isValid()) {
			$downloadImages = $item->download_images ? $item->download_images->to('array') : [];
			unset($item->download_images);

			$this->addOrder($item);
			$item->save();

			$downloadStats = $this->downloadImages($item, $downloadImages);

			Logger::info('items', 'item created', [
				'item_id' => $item->id(),
				'site_id' => $item->site_id,
				'category_id' => $item->parent_id,
				'downloaded_images' => $downloadStats['downloaded_images'],
				'failed_images' => $downloadStats['failed_images']
			]);

			$this->processRemoteMedia($item);

			if ($sendPush) {
				$this->sendPushNotification($item);
			}

			$created = true;
		} else {
			Logger::info('items', 'item cannot be created', [
				'site_id' => $item->site_id,
				'category_id' => $item->parent_id,
				'errors' => $validationResult->errors(),
			]);
			$created = false;
		}

		return [$created, $validationResult->errors()];
	}

	protected function processRemoteMedia($item)
	{
		$service = new ProcessRemoteMedia;
		$service->schedule($item);
	}

	protected function downloadImages($item, $downloadImages)
	{
		return array_reduce($downloadImages, function($stats, $downloadImage) use ($item) {
			$image = Model::load('Images')->download($item,
				$downloadImage['url'], $downloadImage);

			if ($image) {
				$stats['downloaded_images'] += 1;
			} else {
				$stats['failed_images'] += 1;
			}

			return $stats;
		}, [
			'downloaded_images' => 0,
			'failed_images' => 0
		]);
	}

	protected function sendPushNotification($item)
	{
		$category = $item->parent();

		if ($item->is_published && $category->notification) {
			$job = WorkerManager::enqueue('push_notification', [
				'item_id' => $item->id(),
				'category_id' => $item->parent_id
			]);
		}
	}

	protected function addOrder($item)
	{
		$lastItem = $item->getLast();

		if ($lastItem) {
			$item->order = $lastItem->order + 1;
		} else {
			$item->order = 1;
		}
	}
}
