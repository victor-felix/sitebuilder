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
        switch ($job) {
            case 'import' :
                $job = 'run_import.php';
                break;
            case 'geocode' :
                $job = 'run_geocode.php';
                break;
        }
        if (!(bool)exec('crontab -l | grep "'. $job .'"')) {
            self::initCronJobs();
        }
        return  (bool)exec('crontab -l | grep "'. $job .'"');
    }
    
    public static function initCronJobs($jobs = null, $canInit = true)
    {
        $scripts[] = array(
                'time' => '*/2 * * * *',
                'script' => 'run_import.php',
        );
        $scripts[] = array(
                'time' => '*/5 * * * *',
                'script' => 'run_geocode.php',
        );
        
        $jobs = $jobs ? $jobs : $scripts;
        
        $cronFilePath = APP_ROOT .'/config/cron';
        if (!is_file($cronFilePath)) {
            $file = fopen($cronFilePath, 'w');
            fwrite($file, 'MAIL=""' . PHP_EOL);
            foreach ($jobs as $job) {
                $line = $job['time'] . ' php ' . LIB_ROOT 
                . '/script/' . $job['script'] . ' > /dev/null' . PHP_EOL;
                fwrite($file, $line);
            }
            fclose($file);
            chmod($cronFilePath,0777);
        }
        
        if ($canInit) {
            exec("crontab $cronFilePath");
        }
    }
    
}
