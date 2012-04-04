<?php
namespace utils;

abstract class Work
{
    protected $stoping = false;
    
    abstract public function init();
    
    abstract public function run();
    
    public function start()
    {
        try {
            $this->init();
            $this->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    
}
