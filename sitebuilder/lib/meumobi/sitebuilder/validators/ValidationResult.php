<?php
namespace meumobi\sitebuilder\validators;

class ValidationResult
{
	protected $errors;

	public function addError($error)
	{
		$this->erros[] = $error;
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
