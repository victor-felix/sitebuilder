<?php
namespace meumobi\sitebuilder\validators;

interface Validatable
{
	public function validate(Validator $validator,array &$errors);
}
