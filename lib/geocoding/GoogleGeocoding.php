<?php

class GoogleGeocoding
{
	const REGION = 'br';
	const LANGUAGE = 'pt-BR';
	const SENSOR = 'false';

	protected static $geocodeUrls;
	protected static $currentUrl = 0;

	public static function geocode($address, $region = self::REGION, $try_again = true)
	{
		$query = http_build_query(array(
			'region' => $region,
			'language' => self::LANGUAGE,
			'sensor' => self::SENSOR,
			'address' => $address
		));

		try {
			return self::request(self::geocodeUrl() . '/maps/api/geocode/json?' . $query);
		} catch (OverQueryLimitException $e) {
			if ($try_again) {
				self::updateGeocodeUrl();
				return self::geocode($address, $region, false);
			} else {
				throw new GeocodingException('query timed out');
			}
		}
	}

	protected static function updateGeocodeUrl()
	{
		self::$currentUrl = (self::$currentUrl + 1) % count(self::$geocodeUrls);
		$log = \KLogger::instance(\Filesystem::path(APP_ROOT . '/log'));
		$log->logInfo('Change geocode url to: %s', self::geocodeUrl());
	}

	protected static function geocodeUrl($goNext = null)
	{
		if (!self::$geocodeUrls) {
			self::$geocodeUrls = (array) Config::read('Geocode.urls');
		}

		if (empty(self::$geocodeUrls)) {
			self::$geocodeUrls []= 'http://maps.googleapis.com';
		}

		return self::$geocodeUrls[self::$currentUrl];
	}

	protected static function request($url)
	{
		$remote = curl_init($url);
		curl_setopt($remote, CURLOPT_HEADER, 0);
		curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($remote);
		curl_close($remote);

		$log = \KLogger::instance(\Filesystem::path(APP_ROOT . '/log'));
		$log->logInfo('Geocode Request: %s', $url);
		$log->logInfo('Geocode Response: %s', $data);

		$json = json_decode($data);

		if ($json->status == 'OK' && !empty($json->results)) {
			return $json;
		} elseif ($json->status == 'OVER_QUERY_LIMIT') {
			$log->logInfo('Geocode: over query limit');
			throw new OverQueryLimitException('query limit exceeded');
		} else {
			throw new GeocodingException('could not find results');
		}
	}
}

class OverQueryLimitException extends Exception {}

class GeocodingException extends Exception {}
