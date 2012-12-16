<?php

require 'lib/core/security/Sanitize.php';
require 'lib/core/storage/Session.php';
require 'lib/utils/Auth.php';

class AppController extends Controller {
	protected function beforeFilter() {
		if ($this->isXhr()) {
			$this->autoLayout = false;
		}
	}

	public function getCurrentSite() {
		if(Auth::loggedIn()) {
			if ($site = Auth::user()->site()) {
				return $site;
			} else {
				Auth::user()->registerNewSite ();
				$this->redirect ( '/sites/register' );
				Session::write ( 'Users.registering', '/sites/register' );
			}
		}
		else {
			Session::flash('Auth.redirect', Mapper::here());
			$this->redirect('/users/login');
		}
	}

	public function getSegment() {
		return MeuMobi::currentSegment();
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
