<?php
namespace utils;

abstract class Work
{
    protected $log;

    abstract public function init();
    abstract public function run();

    public function start()
    {
        $this->log = \KLogger::instance(\Filesystem::path('log'));
        try {

            $this->init();
            $this->run();
        } catch (Exception $e) {
            $this->log->logError($e->getMessage());
        }
    }

    public static function check($job)
    {  
        
        if (!(bool)exec('crontab -l | grep "'. self::getScript($job) .'"')) {
            self::initCronJobs();
        }
        return  (bool)exec('crontab -l | grep "'. self::getScript($job) .'"');
    }
    
    public static function initCronJobs($jobs = null, $canInit = true)
    {
        $scripts[] = array(
                'time' => '*/2 * * * *',
                'script' => 'import',
        );
        $scripts[] = array(
                'time' => '*/5 * * * *',
                'script' => 'geocode',
        );
        
        $jobs = $jobs ? $jobs : $scripts;
        
        $cronFilePath = APP_ROOT .'/config/cron';
        exec("crontab -l > $cronFilePath");
        $file = fopen($cronFilePath, 'a');
        foreach ($jobs as $job) {
            $line = $job['time'] . ' php ' . self::getScript($job['script']) . ' > /dev/null' . PHP_EOL;
            fwrite($file, $line);
        }
        fclose($file);
        chmod($cronFilePath,0777);
        
        
        if ($canInit) {
            exec("crontab $cronFilePath");
        }
    }
    
    public static function getScript($job)
    {
        switch ($job) {
        	case 'import' :
        		$job = LIB_ROOT . '/script/run_import.php';
        		break;
        	case 'geocode' :
        		$job = LIB_ROOT . '/script/run_geocode.php';
        		break;
        }
        return $job;
    }
    
}
