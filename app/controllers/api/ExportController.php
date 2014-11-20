<?php

namespace app\controllers\api;

use app\models\Items;
use Model;
use Inflector;

class ExportController extends ApiController {
	public function export() {
		$conditions = array(
			'site_id' => $this->site()->id
		);
		$category_id = $this->request->params['category_id'];
		$category = Model::load('Categories')->firstById($category_id);
		$conditions['parent_id'] = $category->id;
		$type = $conditions['type'] = $category->type;
		$classname = '\app\models\items\\' . Inflector::camelize($type);
		$items = $classname::find('all', array('conditions' => $conditions));

		set_time_limit(0);
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=$category_id.csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$classname::exportTo('csv', $conditions);
		exit;
	}
}
