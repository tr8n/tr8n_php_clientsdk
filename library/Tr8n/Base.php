<?php

#--
# Copyright (c) 2010-2013 Michael Berkovich, tr8nhub.com
#
# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#++

namespace Tr8n;


class Base {
    const API_PATH = '/tr8n/api/';

    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_USERAGENT      => 'tr8n-php-clientsdk',
    );

    function __construct($attributes=array()) {
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
            Logger::instance()->info("POST: " . $opts[CURLOPT_URL]);
            Logger::instance()->info($opts[CURLOPT_POSTFIELDS]);
        } else {
            $opts[CURLOPT_URL] = $options['host'].Application::API_PATH.$path.'?'.http_build_query($params, null, '&');
            Logger::instance()->info("GET: " . $opts[CURLOPT_URL]);
        }


        curl_setopt_array($ch, $opts);

        $result = curl_exec($ch);

//        Logger::instance()->info($result);

        curl_close($ch);

        $data = json_decode($result, true);

        if ($data['error']) {
            throw (new Tr8nException("Error: ".$data['error']));
        }

        return self::processResponse($data, $options);
    }

    public static function processResponse($data, $options = array()) {
        if ($data["results"]) {
            Logger::instance()->info("received " . count($data["results"]) ." result(s)");

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