<?php

namespace meumobi\sitebuilder\entities;

use Inflector;

class VisitorDevice extends Entity
{
	protected $uuid;
	protected $pushId;
	protected $model;

	public function update($updates)
	{
		foreach ($updates as $field => $value) {
			$field = Inflector::camelize($field, true);
			if (property_exists($this, $field)) {
				$this->$field = $value;
			}
		}
	}

	public function uuid()
	{
		return $this->uuid;
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
		return $this->uuid;
	}
}
