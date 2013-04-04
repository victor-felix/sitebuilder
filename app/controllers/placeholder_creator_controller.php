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
			'type' => 'products',
			'image' => 'menu.png'
		));
	}

	public function products()
	{
		$this->createPlaceholder(array(
			'category' => 'Products',
			'item' => 'Lorem Ipsum',
			'type' => 'products',
			'image' => 'products.png'
		));
	}

	public function news()
	{
		$this->createPlaceholder(array(
			'category' => 'News',
			'item' => 'Lorem Ipsum',
			'type' => 'articles',
			'image' => 'news.png'
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
			'type' => $options['type'],
			'site_id' => $this->getCurrentSite()->id,
			'parent_id' => $category->id,
			'title' => s($options['item'])
		));
		$item->save();

		if ($options['image']) {
			$image = Mapper::url('/images/shared/item_placeholders/' . $options['image'], true);
			$image = Model::load('Images')->download($item, $image, array(
				'visible' => 1
			));
		}

		$this->redirect('/business_items/index/' . $category->id);
	}
}
