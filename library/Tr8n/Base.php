<?php
/**
 * Copyright (c) 2013 Michael Berkovich, tr8nhub.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Tr8n;


class Base {
    const API_PATH = '/tr8n/api/';

    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_USERAGENT      => 'tr8n-php-clientsdk',
    );

    /**
     * @param array $attributes
     */
    function __construct($attributes=array()) {
        foreach($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param string $path
     * @param array $params
     * @param array $options
     * @return array
     * @throws Tr8nException
     */
    public static function executeRequest($path, $params = array(), $options = array()) {
        $t0 = microtime(true);

        $ch = curl_init();

        $opts = self::$CURL_OPTS;

        if (!array_key_exists('method', $options)) {
            $options['method'] = 'GET';
        }

        if ($options['method'] == 'POST') {
            $opts[CURLOPT_URL] = $options['host'].Application::API_PATH.$path;
            $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
            Logger::instance()->info("POST: " . $opts[CURLOPT_URL] . '?' . $opts[CURLOPT_POSTFIELDS]);
        } else {
            $opts[CURLOPT_URL] = $options['host'].Application::API_PATH.$path.'?'.http_build_query($params, null, '&');
            Logger::instance()->info("GET: " . $opts[CURLOPT_URL]);
        }

        curl_setopt_array($ch, $opts);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_status != 200) {
            Logger::instance()->error("Got HTTP response: $http_status");
            throw new Tr8nException("Got HTTP response: $http_status");
        }

//        Logger::instance()->info($result);

        curl_close($ch);

        $data = json_decode($result, true);

        if (isset($data['error'])) {
            throw (new Tr8nException("Error: " . $data['error']));
        }

        $t1 = microtime(true);
        $milliseconds = round($t1 - $t0,3)*1000;
        \Tr8n\Logger::instance()->info("Received " . strlen($result) . " chars in " . $milliseconds . " milliseconds");
        return self::processResponse($data, $options);
    }

    /**
     * @param string $data
     * @param array $options
     * @return array
     */
    public static function processResponse($data, $options = array()) {
        if (isset($data['results'])) {
            Logger::instance()->info("received " . count($data["results"]) ." result(s)");

            if (!isset($options["class"])) return $data["results"];

            $objects = array();
            foreach($data["results"] as $json) {
                array_push($objects, self::createObject($json, $options));
            }
            return $objects;
        }

        if (!isset($options["class"])) return $data;
        return self::createObject($data, $options);
    }

    /**
     * @param $data
     * @param $options
     * @return mixed
     */
    public static function createObject($data, $options) {
        if ($options != null && array_key_exists('attributes', $options)) {
            $data = array_merge($data, $options['attributes']);
        }
        return new $options["class"]($data);
    }

    public function toArray($keys=array()) {
        $vars = get_object_vars($this);
//        print_r($vars);

        $results = array();
        if (count($keys) == 0) {
            foreach($vars as $name=>$value) {
                if (is_string($value) || is_numeric($value) || is_bool($value)) {
                    $results[$name] = $value;
                }
            }

            return $results;
        }

        foreach($keys as $key) {
            if (!isset($vars[$key])) continue;
            $results[$key] = $vars[$key];
        }
        return $results;
    }

}