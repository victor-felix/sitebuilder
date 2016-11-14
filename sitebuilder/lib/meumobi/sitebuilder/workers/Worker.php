<?php

namespace meumobi\sitebuilder\workers;

use Inflector;
use Model;
use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;

abstract class Worker
{
  const PRIORITY_HIGH = 2;
  const PRIORITY_LOW = 0;
  
	protected $item;
	protected $job;
	protected $logger;
	protected $params;
	protected $site;

	abstract public function perform();

	public function __construct(array $attrs = [])
	{
		$this->setAttributes($attrs);
	}

	public function setAttributes(array $attrs)
	{
		foreach ($attrs as $key => $value) {
			if (is_string($value)) {
				$value = trim($value);
			}

			$key = Inflector::camelize($key, true);
			$method = 'set' . Inflector::camelize($key);

			if (method_exists($this, $method)) {
				$this->$method($value);
			} else if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	public function job()
	{
		return $this->job;
	}

	protected function getItem()
	{
		if ($this->item) return $this->item;

		$this->item = Items::find('type', ['conditions' => [
			'_id' => $this->params['item_id'],
		]]);

		if (!$this->item) {
			throw new RecordNotFoundException("The item '{$id}' was not found");
		}

		return $this->item;
	}

	protected function getSite()
	{
		if ($this->site) return $this->site;

		return $this->site = Model::load('Sites')
			->firstById($this->getItem()->site_id);
	}
}
