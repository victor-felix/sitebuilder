<?php

use meumobi\sitebuilder\services\ImportCsvService;

require_once 'lib/utils/FileUpload.php';
require_once 'lib/mailer/Mailer.php';

use app\models\Extensions;
use app\models\extensions\Rss;
use app\models\Items;

class Categories extends AppModel
{
	const MAX_IMPORTFILE_SIZE = 300;
	protected $beforeSave = array('setOrder', 'getItemType');
	protected $afterSave = array('importItems', 'updateParentTimestamps');
	protected $beforeDelete = array('deleteChildren',
		'updateParentTimestampsWhenDeleted');
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
		'parent_id' => array(
			array(
				'rule' => 'validParent',
				'message' => 'A valid parent category is required'
			)
		)
	);

	public function __construct($data = array())
	{
		parent::__construct($data);

		if (is_null($this->id) && !isset($this->data['visibility'])) {
			$this->data['visibility'] = true;
			$this->data['populate'] = 'manual';
		}
	}

	public function childrenItems($limit = null)
	{
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

	public function childrenCount()
	{
		return Items::find('count', array('conditions' => array(
			'parent_id' => $this->id
		)));
	}

	public function breadcrumbs()
	{
		$parent_id = $this->parent_id;
		$breadcrumbs = array($this);

		while ($parent_id > 0) {
			$category = $this->firstById($parent_id);
			$breadcrumbs []= $category;
			$parent_id = $category->parent_id;
		}

		return array_reverse($breadcrumbs);
	}

	public function parent()
	{
		if ($this->parent_id && $this->validParent($this->parent_id)) {
			return $this->firstById($this->parent_id);
		}
	}

	public function recursiveById($id, $depth)
	{
		$results = array($this->firstById($id));

		if ($depth > 0) {
			$children = $this->recursiveByParentId($id, $depth - 1);
			$results = array_merge($results, $children);
		}

		return $results;
	}

	public function recursiveByParentId($parent_id, $depth)
	{
		$results = $this->allByParentIdAndVisibility($parent_id, 1);

		if ($depth > 0) {
			foreach ($results as $result) {
				$children = $this->recursiveByParentId($result->id, $depth - 1);
				$results = array_merge($results, $children);
			}
		}

		return $results;
	}

	public function toJSON()
	{
		$data = $this->data;
		$data['items_count'] = $this->childrenCount();
		return $data;
	}

	public function moveUp()
	{
		$this->move('up');
	}

	public function moveDown()
	{
		$this->move('down');
	}

	protected function move($direction)
	{
		if ($direction == 'down') {
			$conditionOrderField = '`order` >';
			$order = '`order` ASC';
			$factor = 1;
		} elseif ($direction == 'up') {
			$conditionOrderField = '`order` <';
			$order = '`order` DESC';
			$factor = -1;
		}

		$previous = $this->first(array(
			'conditions' => array(
				'parent_id' => $this->parent_id,
				'site_id' => $this->site_id,
				'visibility >' => -1,
				$conditionOrderField => $this->order
			),
			'order' => $order
		));

		if ($previous) {
			$this->order += $factor;
			$previous->order = $this->order + ($factor * -1);
			$this->save();
			$previous->save();
		}
	}

	protected function getHighestOrder($parent_id, $site_id)
	{
		$query = $this->connection()->read(array(
			'table' => 'categories',
			'conditions' => array(
				'parent_id' => $parent_id,
				'site_id' => $site_id,
				'visibility >' => -1
			),
			'fields' => 'MAX(`order`) AS highest_order'
		))->fetch();
		$highest = (int) $query['highest_order'];

		return $highest + 1;
	}

	protected function setOrder($data)
	{
		if (!$this->id) {
			$parent_id = isset($data['parent_id']) ? $data['parent_id'] : null;
			$data['order'] = $this->getHighestOrder($parent_id, $data['site_id']);
		}

		return $data;
	}

	protected function getItemType($data)
	{
		if (is_null($this->id)) {
			$site = Model::load('Sites')->firstById($this->site_id);
			$items = (array) $site->itemTypes();

			if (!array_key_exists('type', $data) || !in_array($data['type'], $items)) {
				$data['type'] = $items[0];
			}
		}

		return $data;
	}

	protected function importItems($created)
	{
		if (isset($this->data['import']) && is_uploaded_file($this->data['import']['tmp_name'])) {
			$fileSize = $this->data['import']['size'];
			try {
				if ($fileSize && self::MAX_IMPORTFILE_SIZE < ($fileSize / 1024)
					&& $this->scheduleImport()) {
						return $this->save();
					}
				$import = new ImportCsvService(['logger_path' => 'log/imports.log']);
				$import->setMethod($this->data['import_method']);
				$import->setCategory($this);
				$import->setFile($this->data['import']['tmp_name']);
				$import->import();
			} catch (Exception $e) {
				Session::writeFlash('error', s('Sorry, can\'t import the category items'));
			}
		}
	}

	protected function scheduleImport()
	{
		$uploader = new FileUpload();
		$uploader->path = APP_ROOT . '/uploads/imports';

		$importFile = $uploader->upload($this->data['import'], Security::hash(time()) . '_:original_name');

		$data = array(
			'type' => 'import',
			'params' => array(
				'method' => $this->data['import_method'],
				'site_id' => $this->data['site_id'],
				'category_id' => $this->data['id'],
				'file' => "/uploads/imports/$importFile",
			)
		);

		$job = \app\models\Jobs::create($data);
		if ($job->save()) {
			$this->sendImportMail(array('job' => $job->to('array')));
			Session::writeFlash('success', s('The import was scheduled successfully'));
			return true;
		} else {
			throw new Exception();
		}
	}

	protected function sendImportMail($params = array())
	{
		if (!Config::read('Mail.preventSending')) {
			$segment = MeuMobi::currentSegment();
			$user = Auth::user();
			$default = array(
				'user' => $user,
				'category' => $this,
				'title' => s('[MeuMobi] Category import scheduled'),
			);
			$data = array_merge($default, $params);

			$mailer = new Mailer(array (
				'from' => $segment->email,
				'to' => array($user->email => $user->fullname()),
				'subject' => $data['title'],
				'views' => array('text/html' => 'categories/confirm_import_mail.htm'),
				'layout' => 'mail',
				'data' => $data,
			));
			return $mailer->send();
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
		$site->modified = $date;
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

	public function removeItems($id = null)
	{
		$id = $id ? $id : $this->id;
		$items = Items::find('all', array('conditions' => array(
			'parent_id' => $id
		)));

		foreach ($items as $item) {
			Items::remove(array('_id' => $item->id()));
		}
		if ($this->hasFeed()) {
			Extensions::update(
				[
					'priority' => Rss::PRIORITY_HIGH
				],
				[
					'category_id' => $this->id(),
					'extension' => 'rss',
				]);
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

		$this->removeItems($id);

		Extensions::remove(array(
			'category_id' => $id,
		));

		return $id;
	}

	protected function validParent($value)
	{
		if ($value) {
			return (bool)Model::load('Categories')->count(array(
				'conditions' => array(
					'site_id' => $this->site_id,
					'visibility >' => -1,
					'id' => $value,
				),
			));
		} else if (is_null($value)) {
			return true;
		}
	}
}
