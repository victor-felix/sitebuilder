<?php

namespace app\controllers\api;

use lithium\action\Dispatcher;
use DateTime;

class ApiController extends \lithium\action\Controller {
    protected $beforeFilter = array('log', 'checkToken', 'checkSite');
    protected $site;
    protected $params;

    public function beforeFilter() {
        foreach($this->beforeFilter as $filter) {
            if($this->{$filter}() === false) {
                return false;
            }
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
        if($record instanceof \lithium\data\collection\DocumentSet || is_array($record)) {
            $collection = array();

            foreach($record as $k => $v) {
                $collection []= $this->toJSON($v);
            }

            return $collection;
        }
        else {
            return $record->toJSON();
        }
    }

    protected function checkSite() {
        $this->site = $this->site();
        if(!$this->site) {
            throw new \app\models\sites\MissingSiteException();
        }
    }

    protected function site() {
        $slug = $this->request->params['slug'];
        return \Model::load('Sites')->firstByDomain($slug);
    }

    protected function checkToken() {
        if(\Config::read('Api.ignoreAuth')) return;

        $token = $this->request->env('HTTP_X_AUTHENTICATION_TOKEN');

        if($token != 'c8e75b59161a5922c04ede9a533867e371fa2933') {
            $this->response->status(403);
            return false;
        }
    }

    protected function log() {
        $log = \KLogger::instance(\Filesystem::path('log'));
        $log->logInfo('%s %s', $this->request->env('REQUEST_METHOD'), $this->request->url);
        $log->logInfo('Request Data:\n%s', print_r($this->request->data, true));
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
        if(is_object($object) && get_class($object) == 'lithium\data\collection\DocumentSet') {
            $object = $object->to('array');
        }

        if(is_array($object)) {
            $sum = array_reduce($object, function($value, $current) {
                $current = (object) $current;
                $modified = $current->modified;
                if(!is_numeric($modified)) {
                    $modified = new DateTime($modified);
                    $modified = $modified->getTimestamp();
                }
                return $value + $modified;
            }, 0);

            return md5($sum);
        }
        else {
            return md5($object->modified);
        }
    }
}
