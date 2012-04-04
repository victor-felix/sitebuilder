<?php
namespace utils;

require_once dirname(dirname(dirname(__DIR__))) . '/config/bootstrap.php';
require_once 'config/settings.php';
require_once 'config/connections.php';

class Worker 
{
    public $file;
    protected $delay;
    protected $process;
    protected $tmpDir;
    
    public function __construct($process)
    {
    	set_time_limit(0);
    	$this->process = $process;
    	$this->tmpDir = dirname( dirname( dirname(__FILE__) ) ) . '/tmp/';
    	//echo 'process: ', $process, ' pid: ', getmypid(),  "\n";
    	//echo 'delay: ', $delay, "\n";
    }
    
    public static function canProcess($process = 'import')
    {
        
    }
    
    public function canRun()
    {
    	if (!$this->file) {
    		$this->file = fopen($this->tmpDir . $this->process . '.pid', 'w+');
    		if ($this->file && flock($this->file, LOCK_EX | LOCK_NB)) {
    			return fwrite($this->file, getmypid());
    		}
    	}
    	//echo 'can\'t init',"\n";
    	return false;
    }
    
    public function run()
    {
    	if (!$this->canRun()) {
    		return false;
    	}
		try {
			$workClass = $this->getWorkClass();
			$work = new $workClass();
			$work->start();
		} catch (Exception $e) {
			//echo $e->getMessage();
		}
		$this->stop();
    }
    
    protected function getWorkClass()
    {
    	$class = ucfirst($this->process);
    	require_once 'lib/utils/Works/' . $class . '.php';
    	return 'utils\\'.$class;
    }
    
    protected function stop()
    {
    	if ($this->file) {
    		fclose($this->file);
    		unlink($this->tmpDir . $this->process . '.pid');
    	}
    }   
}