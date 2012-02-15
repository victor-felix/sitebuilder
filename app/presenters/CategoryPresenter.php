<?php

namespace app\presenters;

class CategoryPresenter {
	protected $model;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function __get($key)
	{
		return $this->model->{$key};
	}

	public function __set($key, $value)
	{
		return $this->model->{$key} = $value;
	}

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->model, $method), $args);
	}

	public function toJSON()
	{
		$attr = $this->model->attributes();
		$attr['items_count'] = $this->model->countItems();
		return $attr;
	}
}
