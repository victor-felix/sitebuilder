<?php

namespace app\presenters;

class ExtensionPresenter {
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
		$site = \Model::load('Sites')->firstById($this->model->site_id);
		$keys = array('site_id', 'created', 'modified');
		$attr = $this->model->attributes();
		$attr = array_diff_key($attr, array_flip($keys));
		if ($this->model->extension == 'store-locator') {
			$attr['url'] = \MeuMobi::url('/api/' . $site->domain() . '/categories/' . $this->model->category_id . '/geo/nearest', true);
		}
		return $attr;
	}
}
