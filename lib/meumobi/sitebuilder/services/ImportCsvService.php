<?php
namespace meumobi\sitebuilder\services;
use lithium\data\Connections;
use app\models\Jobs;

abstract class ImportCsvService extends Service {
	const INCLUSIVE = 0;
	const EXCLUSIVE = 1;
	const LOG_CHANNEL = 'sitebuilder.import_csv';

	protected $fields;
	protected $file;
	protected $filePath;
	protected $lastJob;
	protected $method;

	abstract public function import();

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
}
