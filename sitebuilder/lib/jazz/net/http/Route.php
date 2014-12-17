<?php

namespace jazz\net\http;

class Route extends \lithium\net\http\Route {
    protected $_pattern;

    public function parse($request) {
        if(!isset($this->_config['method']) || $request->env('REQUEST_METHOD') == $this->_config['method']) {
            if(strpos($request->url, '?')) {
                $request->url = strstr($request->url, '?', true);
            }

            return parent::parse($request);
        }
        else {
            return false;
        }
    }
}
