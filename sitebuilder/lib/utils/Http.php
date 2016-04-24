<?php

class Http
{
	const HTTP_TIMEOUT = 60;

	public static function request($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::HTTP_TIMEOUT);

		$response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		if ($status >= 200 && $status < 300) {
			return $response;
		} else {
			throw new Exception("http request return error code: $status");
		}
	}
}
