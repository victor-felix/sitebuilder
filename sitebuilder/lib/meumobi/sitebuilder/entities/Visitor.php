<?php

namespace meumobi\sitebuilder\entities;

use meumobi\sitebuilder\entities\VisitorDevice;

use MongoId;
use Security;

class Visitor extends Entity
{
	protected $siteId;
	protected $email;
	protected $hashedPassword;
	protected $authToken;
	protected $lastLogin;
	protected $devices = [];
	protected $groups = [];

	public function siteId()
	{
		return (int)$this->siteId;
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

	public function passwordMatch($password)
	{
		return Security::hash($password, 'sha1') == $this->hashedPassword;
	}

	public function hashedPassword()
	{
		return $this->hashedPassword;
	}

	public function authToken()
	{
		//I(tadeu) updated this to return the persited authToken, but I'm not sure if it is correct, seems confusing to me
		return $this->authToken ? $this->authToken : Security::hash(time() . $this->email(), 'sha1');
	}

	public function lastLogin()
	{
		return $this->lastLogin;
	}
		
	public function setLastLogin($lastLogin)
	{
		$this->lastLogin = $lastLogin;
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