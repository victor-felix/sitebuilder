<?php

use app\models\Extensions;
use meumobi\sitebuilder\Site;

class ExtensionsController extends AppController {
	protected $uses = array('Categories');

	public function add($extension, $category_id = null) {
		$site = $this->getCurrentSite();
		if (!$category_id || !$category = Model::load('Categories')->firstById($category_id)) {
			$data = $_GET;
			$category = new Categories($data);
			$category->site_id = $site->id;
			if (!$data || !$category->validate()) {
				Session::writeFlash('error', s('The category title is required'));
				$this->redirect('/categories/add/' . $category->parent_id);
			}
			$category->save();
		}
		$classname = '\app\models\extensions\\' . Inflector::camelize($extension);
		$extension = $classname::create();

		if(!empty($this->data)) {
			$extension->set($this->data);
			$extension->category_id = $category->id;
			$extension->site_id = $site->id;

			if($extension->validates() && $extension->save()) {
				$message = s('Extension successfully added. Your items are being processed and will be available shortly.');
				if($this->isXhr()) {
					$json = array(
						'success'=>$message,
						'go_back'=>true,
						'refresh'=>'/categories/edit/' . $category->id
					);
					$this->respondToJSON($json);
				}
				else {
					Session::writeFlash('success', $message);
					$this->redirect('/categories/edit/' . $category->id);
				}
			}
		}
		$this->set(compact('extension', 'category'));
	}

	public function edit($id = null) {
		$site = $this->getCurrentSite();
		$extension = Extensions::find('type', array('conditions' => array(
			'_id' => $id,
			'site_id' => $site->id(),
		)));

		$category = Model::load('Categories')->firstById($extension->category_id);

		if(!empty($this->data)) {
			$extension->set($this->data);
			if($extension->validates() && $extension->save()) {
				$message = s('Extension successfully edited');
				if($this->isXhr()) {
					$json = array(
						'success'=>$message,
						'go_back'=>true,
						'refresh'=>'/categories/edit/' . $category->id
					);
					$this->respondToJSON($json);
				}
				else {
					Session::writeFlash('success', $message);
					$this->redirect('/categories/edit/' . $category->id);
				}
			}
		}
		$this->set(compact('extension','category'));
	}

	public function enable($id = null) {
		$site = $this->getCurrentSite();
		$extension = Extensions::find('type', array('conditions' => array(
			'_id' => $id,
			'site_id' => $site->id(),
		)));

		$extension->enabled = $extension->enabled ? 0 : 1;
		if($extension->save()) {
			$message = $extension->enabled
				? s('Extension successfully enabled')
				: s('Extension successfully disabled');

			if($this->isXhr()) {
				$json = array(
					'success'=>$message,
					'go_back'=>true,
					'refresh'=>'/categories/edit/' . $extension->category_id
				);
				$this->respondToJSON($json);
			}
			else {
				Session::writeFlash('success', $message);
				$this->redirect('/categories/edit/' . $extension->category_id);
			}
		}

		$this->set(compact('extension','category'));
	}

	public function delete($id = null) {
		$extension = Items::find('type', array('conditions' => array(
			'_id' => $id
		)));

		$category_id = $extension->category_id;

		$extension->delete();
		$message = s('Item successfully deleted.');

		if($this->isXhr()) {
			$json = array(
				'success'=>$message,
				'go_back'=>true,
				'refresh'=> '/categories/edit/' . $category_id,
			);
			$this->respondToJSON($json);
		}
		else {
			Session::writeFlash('success', $message);
			$this->redirect('/business_items/index/' . $extension->parent_id);
		}
	}
}
