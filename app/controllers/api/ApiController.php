<?php

namespace app\controllers\api;

use lithium\action\Dispatcher;
use DateTime;

class ApiController extends \lithium\action\Controller {
    protected $beforeFilter = array('getSite');
    protected $site;
    protected $params;

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

    public function whenStale($etag, $callback) {
        if($this->isStale($etag)) {
            return $callback();
        }
        else {
            $this->response->status(304);
        }
    }

    public function toJSON($record) {
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

    protected function getSite() {
        $slug = $this->request->params['slug'];
        $this->site = \Model::load('Sites')->firstByDomain($slug);
    }

    protected function param($param, $default = null) {
        if(!$this->params) {
            $this->params = $this->request->query + $this->request->params;
        }

        if(isset($this->params[$param])) {
            return $this->params[$param];
        }
        else {
            return $default;
        }
    }

    protected function etag($object) {
        if(is_array($object)) {
            $sum = array_reduce($object, function($value, $current) {
                $modified = new DateTime($current->modified);
                return $value + $modified->getTimestamp();
            }, 0);

            return md5($sum);
        }
        else {
            return md5($object->modified);
        }
    }
}
