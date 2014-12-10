<?php
namespace meumobi\sitebuilder\entities;

class VisitorDevice extends Entity
{
	protected $uuid;
	protected $pushId;
	protected $model;

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
		return $this->uuid . '' . $this->model;
	}
}
