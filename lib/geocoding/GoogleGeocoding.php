<?php

class GoogleGeocoding {
    const REGION = 'br';
    const FORMAT = 'json';
    const LANGUAGE = 'pt-BR';
    const SENSOR = 'false';
	static $CURRENT_URL = 0;
    
    public static function geocode($address,$region = self::REGION) {
        $query = http_build_query(array(
            'region' => $region,
            //'language' => self::LANGUAGE,
            'sensor' => self::SENSOR,
            'address' => $address
        ));
        $url = self::geocodeUrl() . '/maps/api/geocode/' . self::FORMAT . '?' . $query;

        $log = \KLogger::instance(\Filesystem::path('log'));
        $log->logInfo('Geocode Request: %s', $url);
		
        $request = self::request($url);
        if ($request->status == 'OVER_QUERY_LIMIT' && self::geocodeUrl(true)) {
        	$request = self::geocode($address, $region);
        }
        return $request;
    }
	
    protected static function geocodeUrl($goNext = null)
    {
    	$urls = Config::read('Geocode.urls');
    	
    	if ($urls) {
    		$urls = (array) $urls;
    		//set new url
    		if ($goNext) { 
    			$next = self::$CURRENT_URL + 1;
    			if (isset($urls[$next])) {
    				self::$CURRENT_URL = $next;
    				
    				$log = \KLogger::instance(\Filesystem::path('log'));
    				$log->logInfo('Change geocode url to: %s', $urls[self::$CURRENT_URL]);
    				
    			} else {
    				return false;
    			}
    		}
    		
    		$url = $urls[self::$CURRENT_URL];
    	} else {
    		$url = 'http://maps.googleapis.com';
    	}
    	
    	return $url;
    }
    
    protected static function request($url) {
        $remote = curl_init($url);
        curl_setopt($remote, CURLOPT_HEADER, 0);
        curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($remote);
        curl_close($remote);

        $log = \KLogger::instance(\Filesystem::path('log'));
        $log->logInfo('Geocode Response: %s', $data);

        $json = json_decode($data);
        /*if($json->status != 'OK' || empty($json->results)) {
            throw new Exception('could not find results');
        }*/

        return $json;
    }
}
