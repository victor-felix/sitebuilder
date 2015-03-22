<?php

namespace meumobi\sitebuilder\entities;

use meumobi\sitebuilder\entities\VisitorDevice;
use meumobi\sitebuilder\repositories\RecordNotFoundException;
use meumobi\sitebuilder\validators\Validatable;
use meumobi\sitebuilder\validators\Validator;
use MongoId;
use Security;

class Visitor extends Entity implements Validatable
{
	protected $siteId;
	protected $email;
	protected $firstName;
	protected $lastName;
	protected $hashedPassword;
	protected $authToken;
	protected $lastLogin;
	protected $shouldRenewPassword = false;
	protected $devices = [];
	protected $groups = [];

	public function siteId()
	{
		return (int) $this->siteId;
	}

	public function email()
	{
		return $this->email;
	}

	public function firstName()
	{
		return $this->firstName;
	}

	public function lastName()
	{
		return $this->lastName;
	}

	public function setPassword($password)
	{
		if (!empty($password)) {
			$this->shouldRenewPassword = false;
			return $this->hashedPassword = $this->hashPassword($password);
		}
	}

	public function setRandomPassword()
	{
		$password = Security::randomPassword();
		$this->hashedPassword = $this->hashPassword($password);
		return $password;
	}

	public function passwordMatch($password)
	{
		return $this->hashPassword($password) == $this->hashedPassword;
	}

	protected function hashPassword($password)
	{
		return Security::hash($password, 'sha1');
	}

	public function hashedPassword()
	{
		return $this->hashedPassword;
	}

	public function authToken()
	{
		return $this->authToken
			?: Security::hash(time() . $this->email(), 'sha1');
	}

	public function lastLogin()
	{
		return $this->lastLogin;
	}

	public function setLastLogin($lastLogin)
	{
		$this->lastLogin = $lastLogin;
	}

	public function shouldRenewPassword()
	{
		return $this->shouldRenewPassword;
	}

	public function isPasswordValid()
	{
		return !$this->shouldRenewPassword;
	}

	public function devices()
	{
		return array_unique($this->devices);
	}

	public function addDevice(VisitorDevice $device)
	{
		if (!in_array($device, $this->devices)) $this->devices []= $device;
	}

	public function findDevice($uuid) {
		$device = current(array_filter($this->devices, function($device) use($uuid) {
			return $device->uuid() == $uuid;
		}));

		if (!$device) {
			throw new RecordNotFoundException("The device with uuid '{$uuid}' was not found"); 
		}

		return $device;
	}

	public function groups()
	{
		return array_unique($this->groups);
	}

	public function setGroups($groups)
	{
		if (is_string($groups)) {
			$groups = array_map('trim', explode(',', $groups));
		}
		$this->groups = $groups;
	}

	public function addGroup($group)
	{
		if (!in_array($group, $this->groups)) $this->groups []= $group;
	}

	public function validate(Validator $validator,array &$errors)
	{
		$errors = $validator->brokenRules($this);
		return $validator->isValid($this);
	}
}
