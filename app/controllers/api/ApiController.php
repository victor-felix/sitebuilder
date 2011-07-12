<?php

namespace app\controllers\api;

class ApiController extends \lithium\action\Controller {
    protected $site;
    protected $query;

    public function site($site) {
        if(!$this->site) {
            $this->site = $site;
        }
    }

    protected function toJSON($record) {
        if(is_array($record)) {
            foreach($record as $k => $v) {
                $record[$k] = $this->toJSON($v);
            }
        }
        else if($record instanceof \Model) {
            $record = $record->toJSON();
        }

        return $record;
    }

    protected function param($param, $default = null) {
        if(!$this->query) {
            $this->query = array();

            if(isset($this->request->params['args'])) {
                foreach($this->request->params['args'] as $arg) {
                    if(strpos($arg, ':') !== false) {
                        list($key, $value) = explode(':', $arg);
                        $this->query[$key] = $value;
                    }
                }
            }
        }

        if(isset($this->query[$param])) {
            return $this->query[$param];
        }
        else {
            return $default;
        }
    }
}
