<?php
namespace meumobi\sitebuilder\validators;

interface Validator
{
	public function isValid($entity);
	public function brokenRules($entity);
}
