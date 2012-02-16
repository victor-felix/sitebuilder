<?php
require_once 'lib/utils/Jobs.php';
class Import extends Jobs
{
	protected $lockFile = 'importjob.lock';
	protected $file;
	protected $fileDir = '/public/uploads/imports/';
	protected $fields;

	public function start($entity){
		static::__init();
		$job = static::first(array(
					'conditions' => array('type' => 'import'),
					'order' => 'modified',
					));
			if(!$job) {
				return true;
			}
		return parent::start($job);
	}

	protected function process($entity)
	{
		if(!$this->canProcess($entity)) {
			return true;
		}

		$classname = '\app\models\items\\' . Inflector::camelize($entity->params->type);
		$default = array(
				'parent_id' => $entity->params->category_id,
				'site_id' => $entity->params->site_id,
				'type' => $entity->params->type,
				);
		while($csvLine = $this->next($entity)) {
			$data = $default + $csvLine;
			$item = false;
			if(isset($data['_id'])) {
				$item = $classname::find( 'first', array('conditions' => array( '_id' => $data['_id'])) );
			}
			if(!$item){
				$item = $classname::create();
			}
			$item->set($data);
			$item->save();
		}
		return parent::process($entity);
	}

	protected function stop($entity) 
	{
		if($this->file($entity)) {
			fclose($this->file($entity));
			$file = APP_ROOT . $this->fileDir . $entity->params->file;
			unlink($file);
		}
		$this->delete($entity);
		parent::stop($entity);
	}

	protected function canStart($entity)
	{
		return parent::canStart($entity);
	}

	protected function canProcess($entity)
	{
		if(Model::load('Categories')->exists(array('id' => $entity->params->category_id))
				&& $this->file($entity)) {
			return true;
		}
	}

	protected function next($entity)
	{
		$fields = $this->fields($entity);
		if(!$line = fgetcsv ($this->file($entity), 3000)) {
			return false;
		}
		for($i = 0; $i < count($fields); $i++ ) {
			$item[$fields[$i]] = isset($line[$i])?$line[$i]:'';
		}
		if( isset($item['id']) ) {
			$item['_id'] = $item['id'];
			unset($item['id']);
		}
		return $item;
	}

	protected function fields($entity)
	{
		if(!$this->fields) {
			rewind ($this->file($entity));
			$this->fields = fgetcsv ($this->file($entity));
		}
		return $this->fields;
	}

	protected function file($entity)
	{
		if(!$this->file) {
			$file = APP_ROOT . $this->fileDir . $entity->params->file;
			if( is_file($file) ) {
				$this->file = fopen($file, 'r');
			} else {
				$this->file = false;
			}
		}
		return $this->file;
	}

}
Import::applyFilter('save', function($self, $params, $chain) {
		return Import::beforeSave($self, $params, $chain);
		});
