<?php
namespace meumobi\sitebuilder\validators;

use Model;
use Validation;
use meumobi\sitebuilder\repositories\RecordNotFoundException;

class ItemsPersistenceValidator implements Validator
{
	/*
	 * List of validation rules and error messages tokens, since the message
	 * should be the responsibility of the presentation layer.
	 * http://jeffreypalermo.com/blog/the-fallacy-of-the-always-valid-entity/
	 */
	protected $validations = [
		'parent_id' => [
			'notEmpty' => 'required_category_error',
		],
		'site_id' => [
			'notEmpty' => 'required_site_error',
			'validSite' => 'invalid_site_error'
		],
		'type' => [
			'validType' => 'invalid_type_error'
		],
		'title' => [
			'notEmpty' => 'required_title_error'
		],
		'medias' => [
			'notInvalidMedias' => 'invalid_medias_error'
		],
	];


	public function validate($item)
	{
		$validationResult = new ValidationResult();
		//replicating the spagheth model validation, but I think this can be better or use a third party lib
		foreach ($this->validations as $property => $rules) {
			foreach ($rules as $rule => $message) {
				if (!$this->validateRule($rule, $item->$property, $item)) {
					$validationResult->addError($property, $message);
				}
			}
		}
		return $validationResult;
	}

	protected function validateRule($rule, $value, $item)
	{
		if (method_exists($this, $rule)) {
			return $this->$rule($value, $item);
		}
		return \Validation::$rule($value);
	}

	protected function validSite($siteId, $item)
	{
		try {
			$site = $item->parent()->site();

			return $site->id == $siteId;
		} catch (RecordNotFoundException $e) {
			return false;
		}
	}

	protected function validType($type, $item)
	{
		$category = $item->parent();
		return $category->type == $type;
	}

	protected function notInvalidMedias($medias, $item)
	{
		if (!is_array($medias)) return true;
		$check = ['url', 'type'];
		foreach ($medias as $media) {
			foreach ($check as $field) {
				if (empty($media[$field]))
					return false;
			}
		}
		return true;
	}
}
