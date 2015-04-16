<?php

namespace meumobi\sitebuilder\workers;
use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;//TODO move exceptions for a more generic namespace
abstract class Worker
{
	const LOG_CHANNEL = 'sitebuilder.worker';

	protected $logger;
	protected $job;
	protected $item;
	protected $site;

	abstract public function perform();

	public function __construct(array $attrs = [])
	{
		$this->setAttributes($attrs);
	}

	public function setAttributes(array $attrs)
	{
		foreach ($attrs as $key => $value) {
			if (is_string($value)) $value = trim($value);
			$key = \Inflector::camelize($key, true);
			$method = 'set' . \Inflector::camelize($key);
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

	protected function logger()
	{
		return $this->logger;
	}
	//It may be better use a trait for getItem and getSite methods
	protected function getItem()
	{
		if ($this->item) return $this->item;
		$this->item = Items::find('type', array('conditions' => array(
			'_id' => $this->job()->params['item_id']
		)));
		if (!$this->item) {
			throw new RecordNotFoundException("The item '{$id}' was not found");
		}
		return $this->item;
	}

	protected function getSite()
	{
		if ($this->site) return $this->site;
		return $this->site = \Model::load('Sites')->firstById($this->getItem()->site_id);
	}
}

