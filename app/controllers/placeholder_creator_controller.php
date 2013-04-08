<?php

require_once 'app/models/categories.php';

class PlaceholderCreatorController extends AppController
{
	public $uses = array();

	public function menu()
	{
		$this->createPlaceholder(array(
			'category' => s('Menu'),
			'type' => 'products',
			'item' => array(
				'title' => s('Super Burger'),
				'description' => s('Super Burger is made with 300g of meat, stuffed with pepperoni and cheese platter and served on a sesame seed bun.'),
				'images' => array('menu-01.jpg', 'menu-02.jpg')
			),
		));
	}

	public function products()
	{
		$this->createPlaceholder(array(
			'category' => s('Products'),
			'type' => 'products',
			'item' => array(
				'title' => s('Nikon COOLPIX L810 16.1 MP Digital Camera'),
				'description' => s('The Model L810 is capable of taking pictures in three dimensions, giving a greater sense of reality more than a simple camera, the Nikon Coolpix model carries up your eyes a whole new world through its NIKKOR ED glass lens with images simply perfect!'),
				'price' => s('USD 899,00'),
				'images' => array('products-01.jpg', 'products-02.jpg')
			),
		));
	}

	public function stores()
	{
		$this->createPlaceholder(array(
			'category' => s('Stores'),
			'type' => 'business',
			'item' => array(
				'title' => s('Superdry Vegas'),
				'address' => s('Fashion Mall, 3200 Las Vegas, Las Vegas, NV 89109'),
				'images' => array('stores-01.jpg', 'stores-02.jpg')
			),
		));
	}

	public function news()
	{
		$this->createPlaceholder(array(
			'category' => s('News'),
			'type' => 'articles',
			'item' => array(
				'title' => s('Applause for iPhone 5 buyers'),
				'description' => s('Apple employees applaud iPhone 5 customers as they leave the Apple store in NY.'),
				'images' => array('news-01.jpg', 'news-02.jpg')
			),
		));
	}

	protected function createPlaceholder($options)
	{
		$category = new Categories(array(
			'site_id' => $this->getCurrentSite()->id,
			'title' => $options['category'],
			'type' => $options['type']
		));
		$category->save();

		$images = array_unset($options['item'], 'images');

		$classname = '\app\models\items\\' . Inflector::camelize($options['type']);
		$item = $classname::create(array(
			'type' => $options['type'],
			'site_id' => $this->getCurrentSite()->id,
			'parent_id' => $category->id,
		) + $options['item']);
		$item->save();

		foreach ($images as $image) {
			$image = Mapper::url('/images/shared/item_placeholders/' . $image, true);
			$image = Model::load('Images')->download($item, $image, array(
				'visible' => 1
			));
		}

		$this->redirect('/business_items/index/' . $category->id);
	}
}
