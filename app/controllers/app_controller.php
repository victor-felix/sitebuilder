<?php

require 'lib/core/security/Sanitize.php';
require 'lib/utils/Auth.php';

class AppController extends Controller {
    protected $allowed = array('skins', 'users');

    protected function beforeFilter() {
        $registering = Session::read('Users.registering');
        if(
            $registering &&
            ($registering != $this->param('here') && !in_array($this->param('controller'), $this->allowed))
        ) {
            $this->redirect($registering);
        }
        if($this->isXhr()) {
            $this->autoLayout = false;
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
            Session::flash('Auth.redirect', Mapper::here());
            $this->redirect('/users/login');
        }
    }

    protected function toJSON($record) {
        if(is_array($record)) {
            foreach($record as $k => $v) {
                $record[$k] = $this->toJSON($v);
            }
        }
        else if($record instanceof Model) {
            $record = $record->toJSON();
        }

        return $record;
    }

    protected function respondToJSON($record) {
        header('Content-type: application/json');
        echo json_encode($this->toJSON($record));
        $this->stop();
    }
}

function __($key) {
    $arguments = func_get_args();
    $arguments[0] = I18n::translate($key);
    return call_user_func_array('sprintf', $arguments);
}

function s($key) {
    $arguments = func_get_args();
    $arguments[0] = YamlDictionary::translate($key);
    return call_user_func_array('__', $arguments);
}

function e($text) {
    return Sanitize::html($text);
}
