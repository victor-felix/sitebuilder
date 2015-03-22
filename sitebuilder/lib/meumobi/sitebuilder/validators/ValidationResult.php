<?php
namespace meumobi\sitebuilder\validators;

class ValidationResult
{
	protected $errors;

	public function addError($property, $error)
	{
		$this->errors[$property] = $error;
	}

	public function isValid()
	{
		return empty($this->errors);
	}

	public function errors()
	{
		return $this->errors;
	}
}
