<?php

require 'lib/core/security/Sanitize.php';
require 'lib/utils/Auth.php';

class AppController extends Controller {
    protected function beforeFilter() {
        if($this->isXhr()) {
            $this->autoRender = false;
        }
    }
    
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
        if(Auth::loggedIn()) {
            return Auth::user()->site();
        }
        else {
            $this->redirect('/users/login');
        }
    }

    protected function renderJSON($record) {
        header('Content-type: application/json');
        echo json_encode($record->toJSON());
        $this->stop();
    }
}

function __() {
    $arguments = func_get_args();
    return call_user_func_array('sprintf', $arguments);
}

function e($text) {
    return Sanitize::html($text);
}