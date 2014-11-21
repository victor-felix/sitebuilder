<?php

namespace app\controllers\api;

use app\models\Items;
use Model;
use Inflector;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use app\presenters\VisitorsArrayPresenter;

class ExportController extends ApiController {
	protected $skipBeforeFilter = ['requireVisitorAuth'];

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
		$this->setHeaders($category_id);
		$classname::exportTo('csv', $conditions);
		exit;
	}

	public function visitors() {
		$repo = new VisitorsRepository();
		$visitors = $repo->findBySiteId($this->site->id);
		$presenter = new VisitorsArrayPresenter($visitors);
		$this->setHeaders('visitors');
		exit($presenter->toCSV());
	}

	protected function setHeaders($filename) {
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=$filename.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
}
