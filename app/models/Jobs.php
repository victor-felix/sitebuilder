<?php
namespace app\models;

class Jobs extends \lithium\data\Model
{
    protected static $running_processes = array();
    protected $getters = array();
    protected $setters = array();

    protected $_meta = array(
        'name' => null, 
        'title' => null, 
        'class' => null, 
        'source' => 'jobs', 
        'connection' => 'default', 
        'initialized' => false, 
        'key' => '_id', 
        'locked' => false
    );

    protected $_schema = array(
        '_id' => array('type' => 'id'), 
        'type' => array('type' => 'string', 'null' => false), 
        'params' => array('type' => 'array', 'default' => 0), 
        'created' => array('type' => 'date', 'default' => 0), 
        'modified' => array('type' => 'date', 'default' => 0)
    );

    public static function isRunning($process)
    {
        if (isset(self::$running_processes[$process])) {
            return self::$running_processes[$process];
        }
        
    	$tmp = LIB_ROOT . '/tmp/';
    	$filePath = $tmp . $process . '.pid';
    	if (file_exists($filePath) && $file = fopen($filePath, 'r')) {
        	if (!flock($file, LOCK_EX | LOCK_NB)) {
        	    self::$running_processes[$process] = fgets($file, 100);
        		fclose($file);
        		return self::$running_processes[$process];
        	} else {
        	    fclose($file);
        	}
    	}
    	
    }
    
    public static function addTimestamps ($self, $params, $chain)
    {
        $item = $params['entity'];
        if (! $item->_id) {
            $item->created = date('Y-m-d H:i:s');
        }
        
        $item->modified = date('Y-m-d H:i:s');
        return $chain->next($self, $params, $chain);
    }
}

Jobs::applyFilter('save', function ($self, $params, $chain){
    return Jobs::addTimestamps($self, $params, $chain);
});