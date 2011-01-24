<?php

class GoogleGeocoding {
    const REGION = 'br';
    const FORMAT = 'json';
    const LANGUAGE = 'pt-BR';
    const SENSOR = 'false';
    
    public static function geocode($address) {
        $query = http_build_query(array(
            'region' => self::REGION,
            'language' => self::LANGUAGE,
            'sensor' => self::SENSOR,
            'address' => $address
        ));
        $url = 'http://maps.googleapis.com/maps/api/geocode/' . self::FORMAT . '?' . $query;

        return self::request($url);
    }
    
    protected static function request($url) {
        $remote = curl_init($url);
        curl_setopt($remote, CURLOPT_HEADER, 0);
        curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($remote);
        curl_close($remote);
        
        $json = json_decode($data);
        if($json->status != 'OK' || empty($json->results)) {
            throw new Exception('could not find results');
        }
        
        return $json;
    }
}