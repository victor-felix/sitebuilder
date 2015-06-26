<?php

namespace meumobi\sitebuilder\services;

use Inflector;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\validators\ItemsPersistenceValidator;

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
		list($addMediaFileSize, $sendPush) = $this->validateOptions($options, ['addMediaFileSize', 'sendPush']);

		if ($validationResult->isValid()) {
			$this->addOrder($item);
			$item->save();

			Logger::info('items', 'item created', [
				'item id' => $item->id(),
				'site id' => $item->site_id,
				'category id' => $item->parent_id,
			]);

			if ($addMediaFileSize) {
				$this->addMediaFileSize($item);
			}

			if ($sendPush) {
				$this->sendPushNotification($item);
			}

			$created = true;
		} else {
			Logger::info('items', 'item can`t be created', [
				'site id' => $item->site_id,
				'category id' => $item->parent_id,
				'errors' => $validationResult->errors(),
			]);
			$created = false;
		}

		return [$created, $validationResult->errors()];
	}

	protected function validateOptions($options, $validOptions)
	{
		$invalidOptions = array_diff(array_keys($options), $validOptions);

		if ($invalidOptions) {
			throw new Exception('invalid options: ' . implode(', ', $invalidOptions));
		}

		return array_map(function($option) use ($options) {
			return isset($options[$option]) ? $options[$option] : null;
		}, $validOptions);
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
					'push enabled in category' => $category->notification
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
