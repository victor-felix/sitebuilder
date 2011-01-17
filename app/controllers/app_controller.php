<?php

require 'lib/core/security/Sanitize.php';

class AppController extends Controller {
    public static function load($name, $instance = false) {
        $filename = 'app/controllers/' . Inflector::underscore($name) . '.php';
        $name = basename($name);
        if(!class_exists($name) && Filesystem::exists($filename)) {
            require_once $filename;
        }
        if(class_exists($name)) {
            if($instance) {
                return new $name();
            }
            else {
                return true;
            }
        }
        else {
            throw new MissingControllerException(array(
                'controller' => $name
            ));
        }
    }
    
    protected function getCurrentSite() {
        return Model::load('Sites')->first();
    }
}

function __() {
    $arguments = func_get_args();
    return call_user_func_array('sprintf', $arguments);
}