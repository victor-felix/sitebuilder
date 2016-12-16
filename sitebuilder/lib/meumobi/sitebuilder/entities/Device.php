<?php

namespace meumobi\sitebuilder\entities;

use Inflector;

class Device extends Entity
{
	protected $uuid;
	protected $userId;
	protected $siteId;
	protected $pushId;
	protected $playerId;
	protected $model;
	protected $manufacturer;
	protected $platform;
	protected $platformVersion;
	protected $appVersion;
	protected $appBuild;
	protected $created;
	protected $modified;

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

	public function userId()
	{
		return $this->userId;
	}

	public function siteId()
	{
		return $this->siteId;
	}

	public function pushId()
	{
		return $this->pushId;
	}

	public function playerId()
	{
		return $this->playerId;
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

	public function manufacturer()
	{
		return $this->manufacturer;
	}

	public function platform()
	{
		return $this->platform;
	}

	public function platformVersion()
	{
		return $this->platformVersion;
	}

	public function created()
	{
		return $this->created;
	}

	public function setCreated($created)
	{
		$this->created = $created;
	}

	public function modified()
	{
		return $this->modified;
	}

	public function setModified($modified)
	{
		$this->modified = $modified;
	}

	public function __toString()
	{
		return (string) $this->uuid;
	}
}
