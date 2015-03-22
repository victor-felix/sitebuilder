<?php
namespace meumobi\sitebuilder\validators;

interface Validator
{
	/*
	 * @return @class ValidationResult
	 */
	public function validate($entity);
}
