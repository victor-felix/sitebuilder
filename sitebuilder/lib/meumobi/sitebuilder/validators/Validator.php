<?php
namespace meumobi\sitebuilder\validators;

interface Validator
{
	public function IsValid($entity);
	public function BrokenRules();
}
