<?php
namespace meumobi\sitebuilder\validators;

class ItemsPersistenceValidator implements Validator
{
	/*
	 * List of validation rules and error messages tokens, since the message 
	 * should be the responsibility of the presentation layer.
	 * http://jeffreypalermo.com/blog/the-fallacy-of-the-always-valid-entity/
	 */
	protected $validations = [
		'parent_id' => [
			'notEmpty' => 'required_value_error_message',
		],
		'site_id' => [
			'notEmpty' => 'required_value_error_message'
		],
		'title' => [
			'notEmpty' => 'required_value_error_message'
		],
		'medias' => [
			'notEmptyMedias' => 'required_media_error_message'
		],
	];


	public function validate($entity)
	{
		$validationResult = new ValidationResult();
		//replicating the spagheth model validation, but I think this can be better or use a third party lib
		foreach ($this->validations as $property => $rules) {
			foreach ($rules as $rule => $message) {
				if (!$this->validateRule($rule, $entity->$property)) {
					$validationResult->addError($property, $message);
				}
			}
		}
		return $validationResult;
	}

	protected function validateRule($rule, $value)
	{
		if (method_exists($this, $rule)) {
			return $this->$rule($value);	
		}
		return \Validation::$rule($value);
	}

	protected function notEmptyMedias($medias)
	{
		$check = ['title', 'url', 'type'];
		foreach ($medias as $media) {
			foreach ($check as $field) {
				if (empty($media[$field]))
					return false;
			}
		}
		return true;
	}
}
