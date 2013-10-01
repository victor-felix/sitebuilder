<?php
namespace meumobi\sitebuilder\services;
use lithium\data\Connections;
use app\models\Jobs;

class ImportCsvService {
	const INCLUSIVE = 0;
	const EXCLUSIVE = 1;
	
	protected $options;
	protected $logger;
	protected $fields;
	protected $file;
	protected $filePath;
	protected $lastJob;
	protected $method;
	protected $category;
	
	public function __construct(array $options = [])
	{
		$this->options = $options;
	}

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

	public function import()
	{
		$startTime = time();
		$imported = 0;
		$classname = '';
		while ($item = $this->getNextItem()) {
			$classname = '\app\models\items\\' .
				\Inflector::camelize($this->getCategory()->type);
			if (isset($item['id'])) {
				$record = $classname::find('first', array(
					'conditions' => array(
						'_id' => $item['id']
					),
				));
			}
			if (!$record) {
				$record = $classname::create();
			}
		
			$item['parent_id'] = $this->getCategory()->id;
			$item['site_id'] = $this->getCategory()->site_id;
			$item['type'] = $this->getCategory()->type;
			$record->set($item);
			$record->save();
			$imported++;
		}

		if (self::EXCLUSIVE == $this->method && $imported) {
			//remove all items creates before import start
			$classname::remove(
				array(
					'parent_id' => $this->getCategory()->id,
					'created' => array(
						'$lt' => new \MongoDate($startTime),
					),
				)
			);
		}
		fclose($this->getFile());
		unlink($this->filePath);
		return $imported;
	}

	protected function getNextItem()
	{
		$fields = $this->getFields();
		if (!$row = fgetcsv($this->getFile(), 3000)) {
			return false;
		}
		foreach ($fields as $key => $field) {
			if (isset($row[$key])) {
				$data[$field] = $row[$key];
			}
		}
		return $data;
	}
	public function setFile($filePath)
	{
		if (is_readable($filePath)) {
			$this->file = fopen($filePath, 'r');
			$this->filePath = $filePath;
			$this->fields = null;
		} else {
			throw new \Exception('file not exist');
		}
	}
	
	public function getFile()
	{
		return $this->file;
	}
	
	public function setMethod($method)
	{
		$this->method = $method;
	}
	
	public function setCategory(\Categories $category)
	{
		$this->category = $category;
	}
	
	public function getCategory()
	{
		if (!$this->category) {
			throw new \Exception("category not set");
		}
		return $this->category;
	}

	protected function getFields()
	{
		if (!$this->fields) {
			rewind($this->getFile());
			$this->fields = fgetcsv($this->getFile(), 3000);
		}
		return $this->fields;
	}

	protected function getNextJob() {
		$job = Jobs::first(array(
			'conditions' => array('type' => 'import'),
			'order' => 'modified',
		));
		if ($job && $job->_id != $this->lastJob) {
			$this->lastJob = $job->_id;
			return $job;
		}
		return false;
	}

	protected function logger()
	{
		if ($this->logger) return $this->logger;

		if (isset($this->options['logger'])) {
			return $this->logger = $this->options['logger'];
		}

		$handler = new \Monolog\Handler\RotatingFileHandler($this->loggerPath());
		$this->logger = new \Monolog\Logger('sitebuilder.import_csv', [$handler]);

		return $this->logger;
	}

	protected function loggerPath()
	{
		return APP_ROOT . '/' . $this->options['logger_path'];
	}
}