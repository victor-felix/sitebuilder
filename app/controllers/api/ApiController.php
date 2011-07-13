<?php

namespace app\controllers\api;

use lithium\action\Dispatcher;

class ApiController extends \lithium\action\Controller {
    protected $site;
    protected $query;
    protected $beforeFilter = array('getSite');

    public function beforeFilter() {
        foreach($this->beforeFilter as $filter) {
            $this->{$filter}();
        }
    }

    public function isStale($etag) {
        return !$this->isFresh($etag);
    }

    public function isFresh($etag) {
        $this->response->headers('ETag', $etag);
        return $this->request->env('HTTP_IF_NONE_MATCH') == $etag;
    }

    protected function getSite() {
        $slug = $this->request->params['slug'];
        $this->site = \Model::load('Sites')->firstBySlug($slug);
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
