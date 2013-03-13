<?php

require_once 'lib/utils/Works/Import.php';
require_once 'lib/utils/FileUpload.php';

use app\models\Extensions;
use app\models\Items;
use utils\Import;

class Categories extends AppModel {
	const MAX_IMPORTFILE_SIZE = 300;
	protected $beforeSave = array('setOrder', 'getItemType');
	protected $afterSave = array('importItems', 'updateParentTimestamps');
	protected $beforeDelete = array('deleteChildren', 'updateOrders', 'updateParentTimestampsWhenDeleted');
	protected $defaultScope = array(
		'order' => '`order` ASC'
	);
	protected $validates = array(
		'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'A non empty title is required'
			),
			array(
				'rule' => array('maxLength', 50),
				'message' => 'The title of a category could contain 50 chars max.'
			)
		),
	);

	public function __construct($data = array()) {
		parent::__construct($data);

		if(is_null($this->id) && !isset($this->data['visibility'])) {
			$this->data['visibility'] = true;
			$this->data['populate'] = 'manual';
		}
	}

	public function childrenItems($limit = null) {
		$type = Inflector::underscore($this->type);
		$classname = '\app\models\items\\' . Inflector::camelize($type);

		return $classname::find('all', array('conditions' => array(
			'parent_id' => $this->id
		), 'limit' => $limit));
	}

	public function hasFeed()
	{
		return $this->populate == 'auto';
	}

	public function childrenCount() {
		return Items::find('count', array('conditions' => array(
			'parent_id' => $this->id
		)));
	}

	public function breadcrumbs() {
		$parent_id = $this->parent_id;
		$breadcrumbs = array($this);

		while($parent_id > 0) {
			$category = $this->firstById($parent_id);
			$breadcrumbs []= $category;
			$parent_id = $category->parent_id;
		}

		return array_reverse($breadcrumbs);
	}

	public function parent()
	{
		if ($this->parent_id) {
			return $this->firstById($this->parent_id);
		}
	}

	public function recursiveById($id, $depth) {
		$results = array($this->firstById($id));

		if($depth > 0) {
			$children = $this->recursiveByParentId($id, $depth - 1);
			$results = array_merge($results, $children);
		}

		return $results;
	}

	public function recursiveByParentId($parent_id, $depth) {
		$results = $this->allByParentIdAndVisibility($parent_id, 1);

		if($depth > 0) {
			foreach($results as $result) {
				$children = $this->recursiveByParentId($result->id, $depth - 1);
				$results = array_merge($results, $children);
			}
		}

		return $results;
	}

	public function toJSON() {
		$data = $this->data;
		$data['items_count'] = $this->childrenCount();
		return $data;
	}

	public function moveUp($steps = 1) {
		$oldOrder = $this->order;
		$previus = $this->findByOrder($oldOrder - $steps);

		if (!$previus) {
			return false;
		}

		$this->order = $previus->order;
		$previus->order = $oldOrder;
		if ($this->save() && $previus->save()) {
			return $this->order;
		}
	}

	public function moveDown($steps = 1) {
		$oldOrder = $this->order;
		$previus = $this->findByOrder($oldOrder + $steps);

		if (!$previus) {
			return false;
		}

		$this->order = $previus->order;
		$previus->order = $oldOrder;
		if ($this->save() && $previus->save()) {
			return $this->order;
		}
	}

	public function getFirst($parent_id = null, $site_id = null) {
		$parent_id = $parent_id ? $parent_id : $this->parent_id;
		$site_id = $site_id ? $site_id : $this->site_id;

		$conditions = array(
			'parent_id' => $parent_id,
			'site_id' => $site_id,
			'visibility >' => -1
		);

		return $this->first(array(
			'conditions' => $conditions,
			'order' => '`order` ASC',
		));
	}

	public function getLast($parent_id = null, $site_id = null) {
		$parent_id = $parent_id ? $parent_id : $this->parent_id;
		$site_id = $site_id ? $site_id : $this->site_id;

		$conditions = array(
				'parent_id' => $parent_id,
				'site_id' => $site_id,
				'visibility >' => -1
		);

		return $this->first(array(
					'conditions' => $conditions,
					'order' => '`order` DESC',
				));
	}

	public function findByOrder($order) {
		if (!(int)$order) {
			return false;
		}

		$conditions = array(
			'`order`' => $order,
			'parent_id' => $this->parent_id,
			'site_id' => $this->site_id,
			'visibility >' => -1
		);

		return $this->first(array(
					'conditions' => $conditions,
					'order' => '`order` DESC',
				));
	}

	protected function setOrder($data)
	{
		if (!$this->id) {
			$last = $this->getLast();
			if ($last) {
				$data['order'] = $last->order + 1;
			} else {
				$data['order'] = 1;
			}
		}
		return $data;
	}

	protected function getItemType($data) {
		if(is_null($this->id)) {
			$site = Model::load('Sites')->firstById($this->site_id);
			$items = (array) $site->itemTypes();

			if(!array_key_exists('type', $data) || !in_array($data['type'], $items)) {
				$data['type'] = $items[0];
			}
		}

		return $data;
	}

	protected function importItems($created) {
		if (isset($this->data['import']) && is_uploaded_file($this->data['import']['tmp_name'])) {
			$fileSize = $this->data['import']['size'];
			if($fileSize && self::MAX_IMPORTFILE_SIZE < ($fileSize / 1024)
				&& $this->scheduleImport()) {
					return $this->save();
			}
			$import = new Import();
			$import->notIsJob();
			$import->setMethod($this->data['import_method']);
			$import->category($this);
			$import->file($this->data['import']['tmp_name']);
			$import->start();
		}
	}

	protected function scheduleImport()
	{
		if (!Import::check('import')) {
			return false;
		}
		$uploader = new FileUpload();
		$uploader->path = APP_ROOT . '/public/uploads/imports';
		try {
			$importFile = $uploader->upload($this->data['import'], Security::hash(time()) . '_:original_name');

			$data = array(
				'type' => 'import',
				'params' => array(
					'method' => $this->data['import_method'],
					'site_id' => $this->data['site_id'],
					'category_id' => $this->data['id'],
					'file' => $importFile,
				)
			);

			$job = \app\models\Jobs::create($data);
			if ($job->save()) {
				$this->sendImportMail(array('job' => $job->to('array')));
				Session::writeFlash('success', s('The import was scheduled successfully'));
				return true;
			} else {
				throw new Exception('Can\'t import file');
			}

		} catch (Exception $e) {
			Session::writeFlash('error', s('Sorry, can\'t import category'.$e->getMessage()));
			return false;
		}
	}

	protected function sendImportMail($params = array()) {
		if (!Config::read ( 'Mail.preventSending' )) {
			require_once 'lib/mailer/Mailer.php';
			$segment = Model::load ( 'Segments' )->firstById (MeuMobi::segment());
			$user = Auth::user();
			$default = array(
				'user' => $user,
				'category' => $this,
				'title' => s('[MeuMobi] Category import scheduled'),
			);
			$data = array_merge($default, $params);

			$mailer = new Mailer (array (
				'from' => $segment->email,
				'to' => array($user->email => $user->fullname ()),
				'subject' => $data['title'],
				'views' => array ('text/html' => 'categories/confirm_import_mail.htm'),
				'layout' => 'mail',
				'data' => $data,
			));
			return $mailer->send ();
		}
	}

	protected function updateParentTimestamps($created)
	{
		$date = date('Y-m-d H:i:s');
		$parent = $this->parent();

		if ($parent) {
			$parent->modified = $date;
			$parent->save();
		}

		$site = Model::load('Sites')->firstById($this->site_id);
		$site->updated = $date;
		$site->save();
	}

	protected function updateParentTimestampsWhenDeleted($id)
	{
		$self = $this->firstById($id);

		$date = date('Y-m-d H:i:s');
		$parent = $self->parent();

		if ($parent) {
			$parent->modified = $date;
			$parent->save();
		}

		$site = Model::load('Sites')->firstById($self->site_id);
		$site->updated = $date;
		$site->save();

		return $id;
	}

	public function removeItems()
	{
		$id = $this->id;
		$items = Items::find('all', array('conditions' => array(
			'parent_id' => $id
		)));

		foreach($items as $item) {
			Items::remove(array('_id' => $item->id()));
		}
	}

	public function enabledExtensions()
	{
		return Extensions::find('all', array('conditions' => array(
			'category_id' => $this->id,
			'enabled' => 1
		)));
	}

	protected function deleteChildren($id)
	{
		$self = $this->firstById($id);

		$categories = $this->allByParentId($id);
		$this->deleteSet(Model::load('Categories'), $categories);

		$this->removeItems();

		Extensions::remove(array(
			'category_id' => $id,
		));

		return $id;
	}
}
