<?php

namespace app\controllers\api;

use lithium\action\Dispatcher;
use lithium\util\Inflector;
use meumobi\sitebuilder\Site;
use DateTime;
use Config;
use Model;

class ApiController extends \lithium\action\Controller {
	protected $beforeFilter = array('log', 'checkSite', 'checkEtag');
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
		else if($record instanceof \meumobi\sitebuilder\Extension) {
			$c = new \app\presenters\ExtensionPresenter($record);
			return $c->toJSON();
		}
		else if($record instanceof \meumobi\sitebuilder\Category) {
			$c = new \app\presenters\CategoryPresenter($record);
			return $c->toJSON();
		}
		else {
			return $record->toJSON();
		}
	}
	
	public function postConditions($data, $operators = '', $exclusive = false) {
		$conditions = array();
		
		$opIsArray = is_array($operators);
		foreach ($data as $field => $value) {
			$fieldOperator = $operators;
			if ($opIsArray) {
				if (array_key_exists($field, $operators)) {
					$fieldOperator = $operators[$field];
				} else {
					$fieldOperator = false;
				}
			}
			
			if ($exclusive && $fieldOperator === false) {
				continue;
			}
			
			$fieldOperator = trim($fieldOperator);
			
			//change value if LIKE operator 
			if ($fieldOperator == 'like') {
				$value =  "/$value/iu";
			} 
			
			if ($fieldOperator && $fieldOperator != '=') {
				$value = array($fieldOperator => $value);
			}
			
			$conditions[$field] = $value;
		}
		return $conditions;
	}
	
	protected function checkSite() {
		$this->site = $this->site();
		if(!$this->site) {
			throw new \app\models\sites\MissingSiteException();
		}
	}

	protected function checkEtag()
	{
		$etag = $this->etag($this->site());
		if ($this->isFresh($etag)) {
			$this->response->status(304);
			return false;
		}
	}

	protected function site() {
		$domain = $this->request->params['slug'];
		return \Model::load('Sites')->firstByDomain($domain);
		return Site::findByDomain($domain);
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

	// fuck you lithium
	public function render(array $options = array()) {
		$media = $this->_classes['media'];
		$class = get_class($this);
		$name = preg_replace('/Controller$/', '', substr($class, strrpos($class, '\\') + 1));
		$key = key($options);

		if (isset($options['data'])) {
			$this->set($options['data']);
			unset($options['data']);
		}
		$defaults = array(
			'status'	 => null,
			'location'	 => false,
			'data'		 => null,
			'head'		 => false,
			'controller' => Inflector::underscore($name)
		);
		$options += $this->_render + $defaults;

		if ($key && $media::type($key)) {
			$options['type'] = $key;
			$this->set($options[$key]);
			unset($options[$key]);
		}

		$this->_render['hasRendered'] = true;
		$this->response->type($options['type']);
		$this->response->status($options['status']);
		$this->response->headers('Location', $options['location']);

		if ($options['head']) {
			return;
		}
		$data = $this->_render['data'];
		$media::render($this->response, $data, $options + array('request' => $this->request));
	}

	protected function requireUserAuth()
	{
		if (\Config::read('Api.ignoreAuth')) return;

		$token = $this->request->env('HTTP_X_AUTHENTICATION_TOKEN');

		if (!Model::load('UsersSites')->isUserAuthenticatedOnSite($this->site()->id, $token)) {
			throw new NotAuthenticatedException();
		}
	}
}

class NotAuthenticatedException extends \Exception {
    protected $message = 'authentication required';
}
