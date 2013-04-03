<?php

require_once 'app/models/categories.php';

class PlaceholderCreatorController extends AppController
{
	public $uses = array();

	public function menu()
	{
		$this->createPlaceholder(array(
			'category' => 'Menu',
			'item' => 'Lorem Ipsum',
			'type' => 'products'
		));
	}

	public function products()
	{
		$this->createPlaceholder(array(
			'category' => 'Products',
			'item' => 'Lorem Ipsum',
			'type' => 'products'
		));
	}

	public function news()
	{
		$this->createPlaceholder(array(
			'category' => 'News',
			'item' => 'Lorem Ipsum',
			'type' => 'articles'
		));
	}

	protected function createPlaceholder($options)
	{
		$category = new Categories(array(
			'site_id' => $this->getCurrentSite()->id,
			'title' => s($options['category']),
			'type' => $options['type']
		));
		$category->save();

		$classname = '\app\models\items\\' . Inflector::camelize($options['type']);
		$item = $classname::create(array(
			'type' => $type,
			'site_id' => $this->getCurrentSite()->id,
			'parent_id' => $category->id,
			'title' => s($options['item'])
		));
		$item->save();

		$this->redirect('/business_items/index/' . $category->id);
	}
}
