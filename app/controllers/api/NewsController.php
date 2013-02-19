<?php

namespace app\controllers\api;

use app\models\items\Articles;

class NewsController extends ApiController {
	public function index() {
		$category = $this->site()->newsCategory();

		$items = Articles::find('all', array(
				'conditions' => array(
					'site_id' => $this->site()->id,
					'parent_id' => $category->id
				),
				'limit' => $this->param('limit', 10),
				'order' => array('pubdate' => 'DESC'),
		));

		return $this->toJSON($items);
	}

	public function category($slug = null) {
		$category = $this->site->newsCategory();

		return $this->toJSON($category);
	}
}
