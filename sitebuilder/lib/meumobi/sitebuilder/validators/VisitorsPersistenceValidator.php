<?php
namespace meumobi\sitebuilder\validators;

class VisitorsPersistenceValidator implements Validator
{
	protected $validations = [
		'email' => [
			[
				'rule' => 'notEmpty',
				'message' => 'A non empty email is required'
			],
			[
				'rule' => 'email',
				'message' => 'Please enter a valid email address.'
			]
		],
		'firstName' => [
			[
				'rule' => 'notEmpty',
				'message' => 'A non empty name is required'
			],
		],
		'lastName' => [
			[
				'rule' => 'notEmpty',
				'message' => 'A non empty name is required'
			],
		],
	];


	public function isValid($entity)
	{
		return count($this->BrokenRules($entity)) > 0;
	}

	public function brokenRules($entity)
	{
		$brokenRules = [];
		//replicating the spaghett model validation, but I think this can be better
		foreach ($this->validations as $field => $validations) {
			foreach ($validations as $validation) {
				if (!$this->validateRule($validation['rule'], $entity->$field()))
					$brokenRules[$field] = $validation['message'];
			}
		}
		var_dump($brokenRules);
		return $brokenRules;
	}

	protected function validateRule($rule, $value)
	{
		if(method_exists($this, $rule)) {
			return $this->$rule($value);
		} else {
			return \Validation::$rule($value);
		}
	}
}
