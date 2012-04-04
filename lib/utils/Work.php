<?php
namespace utils;

abstract class Work
{
    protected $log;
    
    abstract public function init();
    
    abstract public function run();
    
    public function start()
    {
        try {
            $this->log = \KLogger::instance(\Filesystem::path('log'));
            $this->init();
            $this->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    
}
