<?php

namespace meumobi\sitebuilder\entities;

use lithium\util\Inflector;

use MongoId;

class Skin
{
	protected $id;
	protected $email;
	protected $password;
	protected $lastLogin;
	protected $devices = array();
	protected $groups = array();

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

	public function id()
	{
		return $this->id ? $this->id->{'$id'} : null;
	}

	public function setId(MongoId $id)
	{
		$this->id = $id;
	}

	public function email()
	{
		return $this->email;
	}

	public function password()
	{
		return $this->password;
	}

	public function lastLogin()
	{
		return $this->lastLogin;
	}

	public function devices()
	{
		return $this->devices;
	}

	public function groups()
	{
		return $this->groups;
	}
}
