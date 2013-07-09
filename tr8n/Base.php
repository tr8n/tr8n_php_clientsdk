<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/5/13
 * Time: 12:05 PM
 * To change this template use File | Settings | File Templates.
 */

namespace tr8n;


class Base {
    const API_PATH = '/tr8n/api/';

    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_USERAGENT      => 'tr8n-php-clientsdk',
    );

    function __construct($attributes=array()) {
        \Tr8n::logger()->info("Constructing ".get_class($this)."...");

        foreach($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    protected static function base64UrlDecode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    protected static function base64UrlEncode($input) {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);
        return $str;
    }

    public static function executeRequest($path, $params = array(), $options = array()) {
        $ch = curl_init();

        $opts = self::$CURL_OPTS;

        if ($options['method'] == 'POST') {
            $opts[CURLOPT_URL] = $options['host'].Application::API_PATH.$path;
            $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
        } else {
            $opts[CURLOPT_URL] = $options['host'].Application::API_PATH.$path.'?'.http_build_query($params, null, '&');
        }

        \Tr8n::logger()->info($opts[CURLOPT_URL]);
        \Tr8n::logger()->info($opts[CURLOPT_POSTFIELDS]);

        curl_setopt_array($ch, $opts);

        $result = curl_exec($ch);

        \Tr8n::logger()->info($result);

        curl_close($ch);

        $data = json_decode($result, true);

        if ($data['error']) {
            throw (new Tr8nException("Error: ".$data['error']));
        }

        return self::processResponse($data, $options);
    }

    public static function processResponse($data, $options = array()) {
        if ($data["results"]) {
            \Tr8n::logger()->info("received ".$data["results"].length." result(s)");

            if (!$options["class"]) return $data["results"];

            $objects = array();
            foreach($data["results"] as $json) {
                array_push($objects, self::createObject($json, $options));
            }
            return objects;
        }

        if (!$options["class"]) return $data;
        return self::createObject($data, $options);
    }

    public static function createObject($data, $options) {
        $obj = new $options["class"]($data);
        if ($options["attributes"]) {
            foreach($options["attributes"] as $key => $value) {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

}