<?php

require 'lib/core/security/Sanitize.php';
require 'lib/core/storage/Session.php';
require 'lib/utils/Auth.php';

class AppController extends Controller
{
	protected $language;

	protected function beforeFilter()
	{
		$this->detectLanguage();
		$this->headers();

		if ($this->isXhr()) {
			$this->autoLayout = false;
		}
	}
	protected function headers() {
		header_remove('X-Powered-By');
	}
	protected function detectLanguage()
	{
		if (Auth::loggedIn()) {
			$this->setLanguage(Auth::user()->language);
		} elseif ($language = $this->param('locale')) {
			$this->setLanguage($language);
		} else {
			$this->setLanguage($this->detectBrowserLanguage());
		}
	}

	protected function detectBrowserLanguage()
	{
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$languages = strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], ';', true);
			$languages = explode(',', $languages);

			foreach ($languages as $language) {
				if ($this->languageExists($language)) {
					return $language;
				}
			}
		}

		return 'en';
	}

	protected function setLanguage($language)
	{
		$this->language = I18n::locale($language);
		$this->set(array('language' => $this->language));
		return $this->language;
	}

	protected function languageExists($language)
	{
		return in_array($language, I18n::availableLanguages());
	}

	public function getCurrentSite()
	{
		if (!$this->redirectIfUnauthenticated()) {
			return Auth::user()->site();
		}
	}

	public function redirectIfUnauthenticated()
	{
		if (Auth::loggedIn()) return false;

		Session::write('Auth.redirect', Mapper::here());
		$this->redirect('/users/login');
	}

	public function getSegment()
	{
		return MeuMobi::currentSegment();
	}

	protected function toJSON($record)
	{
		if(is_array($record)) {
			foreach($record as $k => $v) {
				$record[$k] = $this->toJSON($v);
			}
		} elseif ($record instanceof Model) {
			$record = $record->toJSON();
		}

		return $record;
	}

	protected function respondToJSON($record)
	{
		header('Content-type: application/json');
		echo json_encode($this->toJSON($record));
		$this->stop();
	}

	public function param($key, $default = null)
	{
		if (in_array($key, array_keys($this->params))) {
			return $this->params[$key];
		} else if (in_array($key, array_keys($this->request->query))) {
			return $this->request->query[$key];
		} else {
			return $default;
		}
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
