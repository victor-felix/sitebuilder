<?php

use lithium\data\Connections;

class CreateExtensionsForNewsCategories
{
	public static function migrate($connection)
	{
		$categories = Model::load('Categories')->all(array(
			'conditions' => array('visibility' => -1)
		));

		foreach ($categories as $category) {
			try {
				$extension = \app\models\extensions\Rss::create();
				$extension->set(array(
					'site_id' => $category->site_id,
					'category_id' => $category->id,
					'url' => $category->feed_url,
					'enabled' => (int) !empty($category->feed_url)
				));
				$extension->save();
			} catch (Exception $e) {}
		}
	}
}
