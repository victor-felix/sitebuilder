<?php
namespace meumobi\sitebuilder\entities;
use lithium\util\Inflector;

class VisitorDevice
{
	protected $uiid;
	protected $pushId;
	protected $model;

	public function __construct(array $attrs = [])
	{
		$this->setAttributes($attrs);
	}

	public function setAttributes(array $attrs)
	{
		foreach ($attrs as $key => $value) {
			$key = Inflector::camelize($key, false);
			$method = 'set' . Inflector::camelize($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			} else if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	public function uiid()
	{
		return $this->uiid;
	}

	public function pushId()
	{
		return $this->pushId;
	}

	public function model()
	{
		return $this->model;
	}

  public function __toString()
	{
		return $this->uiid . '' . $this->model;
	}
}
