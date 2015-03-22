<?php
namespace meumobi\sitebuilder\validators;

class VisitorsPersistenceValidator implements Validator
{
	/*
	 * List of validation rules and error messages tokens, since the message 
	 * should be the responsibility of the presentation layer.
	 * http://jeffreypalermo.com/blog/the-fallacy-of-the-always-valid-entity/
	 */
	protected $validations = [
		'email' => [
			'notEmpty' => 'required_value_error_message',
			'email' => 'invalid_email_error_message'
		],
		'firstName' => [
			'notEmpty' => 'required_value_error_message'
		],
		'lastName' => [
			'notEmpty' => 'required_value_error_message'
		],
	];


	public function validate($entity)
	{
		$validationResult = new ValidationResult();
		//replicating the spagheth model validation, but I think this can be better or use a third party lib
		foreach ($this->validations as $property => $rules) {
			foreach ($rules as $rule => $message) {
				if (!$this->validateRule($rule, $entity->$property()))
					$validationResult->addError($property, $message);
			}
		}
		return $validationResult;
	}

	protected function validateRule($rule, $value)
	{
		return \Validation::$rule($value);
	}
}
