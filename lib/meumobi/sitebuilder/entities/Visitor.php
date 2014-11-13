<?php

namespace meumobi\sitebuilder\entities;

use lithium\util\Inflector;
use meumobi\sitebuilder\entities\VisitorDevice;

use MongoId;
use Security;

class Visitor
{
	protected $id;
	protected $siteId;
	protected $email;
	protected $hashedPassword;
	protected $authToken;
	protected $lastLogin;
	protected $devices = [];
	protected $groups = [];

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

	public function siteId()
	{
		return $this->siteId;
	}

	public function email()
	{
		return $this->email;
	}

	public function setPassword($password)
	{
		if (!empty($password)) {
			return $this->hashedPassword = Security::hash($password, 'sha1');
		}
	}

	public function hashedPassword()
	{
		return $this->hashedPassword;
	}

	public function authToken()
	{
		return $this->authToken ?: Security::hash(time(), 'sha1');
	}

	public function lastLogin()
	{
		return $this->lastLogin;
	}

	public function devices()
	{
		return array_unique($this->devices);
	}

	public function addDevice(VisitorDevice $device)
	{
		if (!in_array($device, $this->devices)) $this->devices []= $device;
	}

	public function groups()
	{
		return array_unique($this->groups);
	}

	public function addGroup($group)
	{
		if (!in_array($group, $this->groups)) $this->groups []= $group;
	}
}
