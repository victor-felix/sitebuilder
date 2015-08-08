<?php

namespace meumobi\sitebuilder\services;

use Inflector;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\validators\ItemsPersistenceValidator;
use meumobi\sitebuilder\validators\ParamsValidator;

class ItemCreation
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
		list($addMediaFileSize, $sendPush) = ParamsValidator::validate($options, ['addMediaFileSize', 'sendPush']);

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

			if ($addMediaFileSize) {
				$this->addMediaFileSize($item);
			}

			$this->createMediaThumbnails($item);

			if ($sendPush) {
				$this->sendPushNotification($item);
			}

			$created = true;
		} else {
			Logger::info('items', 'item can`t be created', [
				'site_id' => $item->site_id,
				'category_id' => $item->parent_id,
				'errors' => $validationResult->errors(),
			]);
			$created = false;
		}

		return [$created, $validationResult->errors()];
	}

	protected function addMediaFileSize($item)
	{
		$hasMedias = count($item->medias->to('array'));

		if ($hasMedias) {
			WorkerManager::enqueue('media_filesize', ['item_id' => $item->id()]);
		} else {
			Logger::debug('items', 'not creating media_filesize job', [
				'reason' => 'item has no media',
			]);
		}
	}

	protected function createMediaThumbnails($item)
	{
		$hasMedias = count($item->medias->to('array'));

		if ($hasMedias) {
			$job = WorkerManager::enqueue('media_thumbnailer', ['item_id' => $item->id()]);
		} else {
			Logger::debug('items', 'not creating media_thumbnailer job', [
				'reason' => 'item has no media',
			]);
		}
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
		} else {
			Logger::debug('items', 'not creating push_notification job', [
				'reason' => [
					'published' => $item->is_published,
					'push_enabled_in_category' => $category->notification
				]
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
