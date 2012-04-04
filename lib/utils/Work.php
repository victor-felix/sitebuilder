<?php
require_once dirname(dirname(dirname(__DIR__))) . '/config/bootstrap.php';
require_once 'config/settings.php';
require_once 'config/connections.php';

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
