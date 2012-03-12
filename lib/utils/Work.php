<?php
require_once dirname(dirname(dirname(__DIR__))) . '/config/bootstrap.php';
require_once 'config/settings.php';
require_once 'config/connections.php';

class Work
{
    public function __construct()
    {
        try {
            $this->_init();
            $this->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function _init()
    {

    }

    public function run()
    {

    }
}
