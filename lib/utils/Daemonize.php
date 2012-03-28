<?php
declare(ticks = 1);

function sig_handler($signo)
{
    switch ($signo) {
        case SIGTERM:
        case SIGINT:
            global $_DAEMON_SHOULD_STOP;
            $_DAEMON_SHOULD_STOP = true;
        break;
    }
}

pcntl_signal(SIGTERM, 'sig_handler');
pcntl_signal(SIGINT, 'sig_handler');

class Daemonize
{
    public $file;
    protected $delay;
    protected $process;
    protected $tmpDir;

    public function __construct($process, $delay)
    {
        set_time_limit(0);
        $this->delay = $delay;
        $this->process = $process;
        $this->tmpDir = dirname( dirname( dirname(__FILE__) ) ) . '/tmp/';
        echo 'process: ', $process, ' pid: ', getmypid(),  "\n";
        echo 'delay: ', $delay, "\n";
    }
    
    public static function canProcess($process = 'import')
    {
        if (!extension_loaded('pcntl')) {
            return false;
        }
        
    }
    
    public function canRun()
    {
        if (!$this->file) {
            $this->file = fopen($this->tmpDir . $this->process . '.pid', 'w+');
            if ($this->file && flock($this->file, LOCK_EX | LOCK_NB)) {
                return fwrite($this->file, getmypid());   
            }
        }
        echo 'can\'t init',"\n";
        return false;
    }

    public function run()
    {
        if (!$this->canRun()) {
            return false;
        }
        echo 'running..',"\n";
        while (true) {
            if ($this->shouldStop()) {
                $this->stop();
                echo 'dying...',"\n";
                break;
            }
            try {
                $workClass = $this->getWorkClass();
                $work = new $workClass();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            echo $this->process,' last run at ', date('d-m-Y H:i:s'), ' pid: ',getmypid(),"\n";
            sleep($this->delay);
        }
    }

    public function shouldStop()
    {
        global $_DAEMON_SHOULD_STOP;
        if (isset($_DAEMON_SHOULD_STOP) AND $_DAEMON_SHOULD_STOP) {
            return true;
        }

        return false;
    }
 
    protected function getWorkClass()
    {
        $class = ucfirst($this->process);
        require_once dirname( dirname( dirname(__FILE__) ) ) . '/lib/utils/Works/' . $class . '.php';
        return $class;
    }

    protected function stop()
    {
        if ($this->file) {
            fclose($this->file);
            unlink($this->tmpDir . $this->process . '.pid');
        }
    }
}
