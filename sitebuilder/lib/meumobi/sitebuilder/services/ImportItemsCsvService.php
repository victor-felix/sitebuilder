<?php

namespace meumobi\sitebuilder\services;

use Model;
use app\models\Items;
use app\models\Jobs;
use lithium\data\Connections;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\services\CreateItem;
use meumobi\sitebuilder\services\UpdateItem;

class ImportItemsCsvService extends ImportCsvService {
	protected $category;

	public function call()
	{
		$startTime = microtime(true);
		while ($job = $this->getNextJob()) {
			$log['job_id'] = (string) $job->_id;
			try {
				$category = \Model::load('categories')
					->firstById($job->params->category_id);
				$this->setFile(APP_ROOT . $job->params->file);
				$this->setMethod($job->params->method);
				$this->setCategory($category);
				$log['total_items'] = $this->import();
				$this->logger()->info('csv imported ', $log);
			} catch (\Exception $e) {
			$this->logger()->error('csv import error', [
				'exception' => get_class($e),
				'message' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
					] + $log);
			}
			$job->delete();
		}
		$stats['elapsed_time'] = microtime(true) - $startTime;
		$this->logger()->info('csv import finished ', $stats);
	}

	function clearItems()
	{
		$category = $this->getCategory();
  
		Logger::info('items', 'removing category items', [
			'category_id' => $category->id,
			'site_id' => $category->site_id   
				]);
    
		$category->removeItems();
	}
  
	function getItem($data)
	{
		$item = null;
		$category = $this->getCategory();

		if (isset($data['id'])) {
			$item = Items::find('first', [
				'conditions' => [
					'_id' => $data['id'],
					'parent_id' => $category->id,
					]
			]);
  
			$item->set($data);
		} else {
			$classname = '\app\models\items\\' .
				\Inflector::camelize($category->type);
			$item =  $classname::create();

			$data['parent_id'] = $category->id;
			$data['site_id'] = $category->site_id;
			$data['type'] = $category->type;

			$item->set($data);
		}

		return $item;
	}
  
	function createItem($item)
	{
		$service = new CreateItem();

		return $service->create($item);
	}

	function updateItem($item)
	{
		$service = new UpdateItem();

		return $service->update($item);
	}

	public function import()
	{
		$imported = 0;

		Logger::info('items', 'start importing from csv');

		// TODO: should clear items after to be sure that import will succeed
		if (self::EXCLUSIVE == $this->method) {
			$this->clearItems();
		}

		while ($data = $this->getNextItem()) {
			$item = $this->getItem($data);

			if ($item->id()) {
				$this->updateItem($item);
			} else {
				$this->createItem($item);
			}

			$imported++; 
		}

		fclose($this->getFile());
		unlink($this->filePath);
		Logger::info('items', 'imported items from csv', ['total' => $imported]);

		return $imported;
	}

	public function setCategory($category)
	{
		$this->category = $category;
	}

	public function getCategory()
	{
		if (!$this->category) {
			throw new Exception("category not set");
		}
		return $this->category;
	}
}
