<?php

use meumobi\sitebuilder\Logger;

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
				throw $e;
			}
		}
	}

	protected static function updateGeocodeUrl()
	{
		self::$currentUrl = (self::$currentUrl + 1) % count(self::$geocodeUrls);
		Logger::info('geocode', 'changed geocode service url', ['url' => self::geocodeUrl()]);
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

		$json = json_decode($data);

		Logger::debug('geocode', 'request', ['url' => $url]);
		Logger::debug('geocode', 'response', ['data' => $json]);

		if ($json->status == 'OK' && !empty($json->results)) {
			return $json;
		} elseif ($json->status == 'OVER_QUERY_LIMIT') {
			Logger::info('geocode', 'over query limit');
			throw new OverQueryLimitException('query limit exceeded');
		} else {
			Logger::info('geocode', 'unknown error', ['status' => $json->status]);
			throw new GeocodingException('could not find results');
		}
	}
}

class OverQueryLimitException extends GeocodingException {}

class GeocodingException extends Exception {}
