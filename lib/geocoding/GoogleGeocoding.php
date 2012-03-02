<?php

class GoogleGeocoding {
    const REGION = 'br';
    const FORMAT = 'json';
    const LANGUAGE = 'pt-BR';
    const SENSOR = 'false';

    public static function geocode($address,$region = self::REGION) {
        $query = http_build_query(array(
            'region' => $region,
            //'language' => self::LANGUAGE,
            'sensor' => self::SENSOR,
            'address' => $address
        ));
        $url = 'http://maps.googleapis.com/maps/api/geocode/' . self::FORMAT . '?' . $query;

        $log = \KLogger::instance(\Filesystem::path('log'));
        $log->logInfo('Geocode Request: %s', $url);

        return self::request($url);
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
        if($json->status != 'OK' || empty($json->results)) {
            throw new Exception('could not find results');
        }

        return $json;
    }
}
