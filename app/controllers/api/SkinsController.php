<?php

namespace app\controllers\api;

use lithium\util\Inflector;
use meumobi\sitebuilder\entities\Skin;
use meumobi\sitebuilder\presenters\api\SkinPresenter;
use meumobi\sitebuilder\repositories\SkinsRepository;
use meumobi\sitebuilder\repositories\ThemesRepository;

class SkinsController extends \lithium\action\Controller
{
	protected $skinsRepository;

	public function beforeFilter() {}

	public function index()
	{
		$skins = $this->skinsRepository()->all();
		return SkinPresenter::presentSet($skins);
	}

	public function show()
	{
		$skin = $this->skinsRepository()->find($this->request->get('params:id'));
		return SkinPresenter::present($skin);
	}

	public function create()
	{
		$skin = new Skin($this->request->data);
		if ($this->skinsRepository()->create($skin)) {
			$this->response->status(201);
			return SkinPresenter::present($skin);
		} else {
			$this->response->status(422);
		}
	}

	public function update()
	{
		$skin = $this->skinsRepository()->find($this->request->get('params:id'));
		unset($this->request->data['id']);
		$skin->setAttributes($this->request->data);
		if ($this->skinsRepository()->update($skin)) {
			$this->response->status(200);
			return SkinPresenter::present($skin);
		} else {
			$this->response->status(422);
		}
	}

	public function destroy()
	{
		$skin = $this->skinsRepository()->find($this->request->get('params:id'));
		if ($this->skinsRepository()->destroy($skin)) {
			$this->response->status(200);
		} else {
			$this->response->status(422);
		}
	}
	
	protected function skinsRepository()
	{
		if ($this->skinsRepository) return $this->skinsRepository;

		return $this->skinsRepository = new SkinsRepository();
	}

	protected function param($param, $default = null) {
		if(!$this->params) {
			$this->params = $this->request->query + $this->request->params;
		}

		if(isset($this->params[$param])) {
			return $this->params[$param];
		}
		else {
			return $default;
		}
	}

	public function render(array $options = array()) {
		$media = $this->_classes['media'];
		$class = get_class($this);
		$name = preg_replace('/Controller$/', '', substr($class, strrpos($class, '\\') + 1));
		$key = key($options);

		if (isset($options['data'])) {
			$this->set($options['data']);
			unset($options['data']);
		}
		$defaults = array(
			'status' => null,
			'location' => false,
			'data' => null,
			'head' => false,
			'controller' => Inflector::underscore($name)
		);
		$options += $this->_render + $defaults;

		if ($key && $media::type($key)) {
			$options['type'] = $key;
			$this->set($options[$key]);
			unset($options[$key]);
		}

		$this->_render['hasRendered'] = true;
		$this->response->type($options['type']);
		$this->response->status($options['status']);
		$this->response->headers('Location', $options['location']);

		if ($options['head']) {
			return;
		}
		$data = $this->_render['data'];
		$media::render($this->response, $data, $options + array('request' => $this->request));
	}
}
