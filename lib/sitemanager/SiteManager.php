<?php

class SiteManager
{
	public static function create($domain, $instance)
	{
		if (Config::read('SiteManager.url')) {
			return self::request('PUT', $domain, array('instance' => $instance));
		}
	}

	public static function update($previous, $domain, $instance)
	{
		if (Config::read('SiteManager.url')) {
			if ($previous != $domain) {
				self::delete($previous);
				self::create($domain, $instance);
				return true;
			}
			return false;
		}
	}

	public static function delete($domain)
	{
		if (Config::read('SiteManager.url')) {
			return self::request('DELETE', $domain);
		}
	}

	protected static function request($method, $domain, $data = null)
	{
		$base = Config::read('SiteManager.url');
		$url = "{$base}/domain/{$domain}";

		$remote = curl_init($url);
		curl_setopt($remote, CURLOPT_HEADER, 0);
		curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($remote, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($remote, CURLOPT_POSTFIELDS, $data);
		$results = curl_exec($remote);
		$status = curl_getinfo($remote, CURLINFO_HTTP_CODE);
		curl_close($remote);

		if ($status >= 200 && $status < 300) {
			return json_decode($results);
		}
	}
}
