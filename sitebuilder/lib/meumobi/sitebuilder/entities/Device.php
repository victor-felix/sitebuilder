<?php

namespace meumobi\sitebuilder\entities;

use Inflector;

class Device extends Entity
{
	protected $uuid;
	protected $userId;
	protected $pushId;
	protected $model;
	protected $platform;
	protected $platformVersion;
	protected $appVersion;
	protected $appBuild;

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

	public function appVersion()
	{
		return $this->appVersion;
	}

	public function appBuild()
	{
		return $this->appBuild;
	}

	public function model()
	{
		return $this->model;
	}

	public function platform()
	{
		return $this->platform;
	}

	public function version()
	{
		return $this->version;
	}

	public function __toString()
	{
		return (string) $this->uuid;
	}
}
