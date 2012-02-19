<?php

class Jobs extends \lithium\data\Model
{
	protected $lockDir = 'tmp/';
	protected $lockFile = 'job.lock';
	protected $getters = array();
	protected $setters = array();
	protected $_meta = array(
		'name' => null,
		'title' => null,
		'class' => null,
		'source' => 'jobs',
		'connection' => 'default',
		'initialized' => true,
		'key' => '_id',
		'locked' => false
	);

	protected $_schema = array(
		'_id'  => array('type' => 'id'),
		'type'  => array('type' => 'string', 'default' => 0),
		'params' => array('type' => 'array', 'default' => array()),
		'created'  => array('type' => 'date', 'default' => 0),
		'modified'  => array('type' => 'date', 'default' => 0),
	);

	public static function beforeSave($self, $params, $chain) 
	{
		$item = $params['entity'];
		if (!$item->id) {
			$item->created = date('Y-m-d H:i:s');
		}
		$item->modified = date('Y-m-d H:i:s');
		$item->type = strtolower(get_called_class());
		return $chain->next($self, $params, $chain);
	}

	public function start($entity)
	{
		static::__init();
		set_time_limit(0);
		if (!$this->canStart($entity)) {
			return false;
		}
		if ( !Filesystem::write(
			$this->lockDir . $this->lockFile, 
			md5($this->lockDir . $this->lockFile)) ) {
			throw new Exception('Sorry, can\'t create lock file');
		}
		if ($this->process($entity)) {
			$this->stop($entity);
			return true;
		}
	}

	protected function process($entity)
	{
		return true;
	}

	protected function canStart($entity)
	{
		if (Filesystem::read($this->lockDir . $this->lockFile) 
			!= md5($this->lockDir . $this->lockFile)) {
			return true;
		}
	}

	protected function stop($entity) 
	{
		Filesystem::delete($this->lockDir . $this->lockFile);
	}
}
