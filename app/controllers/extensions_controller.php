<?php

use app\models\Extensions;
use meumobi\sitebuilder\Site;

class ExtensionsController extends AppController {
	protected $uses = array('Categories');
		
	public function add($extension, $category_id = null) {
		$site = $this->getCurrentSite();
		$category = Model::load('Categories')->firstById($category_id);
		$classname = '\app\models\extensions\\' . Inflector::camelize($extension);
		$extension = $classname::create();
		
		if(!empty($this->data)) {
			$extension->set($this->data);
			$extension->category_id = $category->id;
			$extension->site_id = $site->id;
			
			if($extension->save()) {
				$message = s('Extension successfully added.');
				if($this->isXhr()) {
					$json = array(
							'success'=>$message,
							'go_back'=>true,
							'refresh'=>'/categories/index/' . $category_id
					);
					$this->respondToJSON($json);
				}
				else {
					Session::writeFlash('success', $message);
					$this->redirect('/categories/index/' . $category_id);
				}
			}
		}
		$this->set(compact('extension','category'));
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
			if($extension->save()) {
				$message = s('Extension successfully added.');
				if($this->isXhr()) {
					$json = array(
							'success'=>$message,
							'go_back'=>true,
							'refresh'=>'/categories/index/' . $category_id
					);
					$this->respondToJSON($json);
				}
				else {
					Session::writeFlash('success', $message);
					$this->redirect('/categories/index/' . $category_id);
				}
			}
		}
		$this->set(compact('extension','category'));
	}

	public function delete($id = null) {
		$extension = Items::find('first', array('conditions' => array(
			'_id' => $id
		)));
		$parent_id = $extension->parent_id;
		Items::remove(array('_id' => $id));
		$message = s('Item successfully deleted.');

		if($this->isXhr()) {
			$json = array(
				'success'=>$message,
				'go_back'=>true,
				'refresh'=>'/business_items/index/' . $parent_id
			);
			$this->respondToJSON($json);
		}
		else {
			Session::writeFlash('success', $message);
			$this->redirect('/business_items/index/' . $extension->parent_id);
		}
	}
}
