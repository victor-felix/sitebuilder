<?php

namespace app\controllers\api;

use app\presenters\ExtensionPresenter;
use meumobi\sitebuilder\Extension;
use meumobi\sitebuilder\Site;

class ExtensionsController extends ApiController
{
	protected function query($key)
	{
		if (isset($this->request->query[$key])) {
			return $this->request->query[$key];
		}
	}

	protected function site()
	{
		return Site::findByDomain($this->request->params['slug']);
	}

	public function index()
	{
		$extensions = $this->site()->extensions();

		return $this->toJSON($extensions);
	}

	public function show()
	{
		$extension = $this->site()->findExtension($this->request->params['id']);
		$extension = new ExtensionPresenter($extension);

		return $extension->toJSON();
	}

	public function create()
	{
		$extension = $this->site()->buildExtension($this->request->data);

		if ($extension->save()) {
			$this->response->status(201);
			$extension = new ExtensionPresenter($extension);
			return $extension->toJSON();
		} else {
			$this->response->status(422);
		}
	}

	public function update()
	{
		$extension = \app\models\Extensions::find('first', array('conditions' => array(
			'_id' => $this->request->params['id'],
		)));

		$extension->set($this->request->data);

		if ($extension->save()) {
			$this->response->status(200);
			$extension = new ExtensionPresenter(new Extension($extension->to('array')));
			return $extension->toJSON();
		} else {
			$this->response->status(422);
		}
	}

	public function destroy()
	{
		\app\models\Extensions::remove(array(
			'_id' => $this->request->params['id']
		));
		$this->response->status(200);
	}
}

