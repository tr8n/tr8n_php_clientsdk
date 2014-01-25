<?php
/**
 * Copyright (c) 2014 Michael Berkovich, http://tr8nhub.com
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

use Tr8n\Utils\StringUtils;

require_once "Logger.php";
require_once "Application.php";

class Config {
    /**
     * @var string[]
     */
    public $config;

    /**
     * @var Application
     */
    public $application;

    /**
     * @var string
     */
    public $default_locale;

    /**
     * @var int
     */
    public $default_level;

    /**
     * @var string[]
     */
    public $default_tokens;

    /**
     * @var mixed
     */
    public $current_user;

    /**
     * @var Language
     */
    public $current_language;

    /**
     * @var Translator
     */
    public $current_translator;

    /**
     * @var Source
     */
    public $current_source;

    /**
     * @var Component
     */
    public $current_component;

    /**
     * @var array
     */
    private $block_options;

    private $rules_engine;
    private $token_classes;

    // Allows for setting a custom config class
    public static function init($config) {
        static $inst = null;
        $inst = $config;
    }

    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new Config();
        }
        return $inst;
    }

    function __construct() {
        $this->application = null;
        $this->default_locale = 'en-US';
        $this->default_level = 0;
        $this->block_options = array();
    }

    public static function configure($configurator) {
        $configurator(self::instance());
    }

    public function configValue($key) {
        if ($this->config == null) {
            $data = file_get_contents($this->configFilePath('config.json'));
            $this->config = json_decode($data, true);
        }

        $value = $this->config;
        $parts = explode(".", $key);
        foreach($parts as $part) {
            if (!isset($value[$part])) return null;
            $value = $value[$part];
        }

        return $value;
    }

    public function updateConfig() {
        file_put_contents($this->configFilePath('config.json'), StringUtils::prettyPrint(json_encode($this->config)));
    }

    public function initApplication($host = null, $client_id = null, $client_secret = null) {
        if ($host == null) { // fallback onto the configuration file
            $host = $this->configValue("application.host");
            $client_id = $this->configValue("application.key");
            $client_secret = $this->configValue("application.secret");
        }

        if ($this->application == null) {
            try {
                $this->application = Application::init($host, $client_id, $client_secret);
            } catch (Tr8nException $e) {
                $this->application = null;
            }
        }
        return $this->application;
    }

    public function initRequest($options = array()) {
        $this->current_language = $this->application->language((isset($options['locale']) ? $options['locale'] : $this->default_locale), true);
        $this->current_translator = (isset($options['translator']) ? $options['translator'] : null);
        $this->current_source = (isset($options['source']) ? $options['source'] : null);
        $this->current_component = (isset($options['component']) ? $options['component'] : null);
    }

    public function completeRequest($options = array()) {
        if (!isset($this->application)) return;
        $this->application->submitMissingKeys();
    }

    public function beginBlockWithOptions($options = array()) {
        array_push($this->block_options, $options);
    }

    public function blockOption($key) {
        if (count($this->block_options) == 0) return null;
        $current_options = $this->block_options[0];
        if (!array_key_exists($key, $current_options)) return null;
        return $current_options[$key];
    }

    public function finishBlockWithOptions() {
        if (count($this->block_options) == 0) return null;
        array_pop($this->block_options);
    }

    public function isEnabled() {
        return ($this->application != null);
    }

    public function isDisabled() {
        return !self::isEnabled();
    }

    public function isLoggerEnabled() {
//        return true;
        return $this->configValue("log.enabled");
    }

    public function loggerFilePath() {
        return __DIR__."/../../log/tr8n.log";
    }

    public function cachePath() {
        return __DIR__."/../../cache/";
    }

    public function cacheVersion() {
        $version = $this->configValue("cache.version");
        return ($version == null ? 0 : $version);
    }

    public function cacheTimeout() {
        $timeout = $this->configValue("cache.timeout");
        return ($timeout == null ? 3600 : $timeout);
    }

    public function incrementCache() {
        $version = $this->cacheVersion();
        $this->config["cache"]["version"] =  $version + 1;
        $this->updateConfig();
    }

    public function loggerSeverity() {
        return Logger::DEBUG;
    }

    public function isCacheEnabled() {
        if ($this->configValue("cache.enabled") === false) {
            return false;
        }

        if ($this->current_translator && $this->current_translator->isInlineModeEnabled())
            return false;

        return true;
    }

    public function cacheAdapterClass() {
        if (!isset($this->config["cache"]["adapter"]))
            $adapter = 'chdb';
        else
            $adapter = $this->config["cache"]["adapter"];

        switch($adapter) {
            case "chdb": return '\Tr8n\Cache\ChdbAdapter';
            case "file": return '\Tr8n\Cache\FileAdapter';
            case "apc": return '\Tr8n\Cache\ApcAdapter';
            case "memcache": return '\Tr8n\Cache\MemcacheAdapter';
        }

        return null;
    }

    public function decoratorClass() {
        return '\Tr8n\Decorators\HtmlDecorator';
    }

    public function defaultSource() {
        return $_SERVER["REQUEST_URI"];
    }

    public function configFilePath($file_name) {
        return __DIR__."/../../config/" . $file_name;
    }

    // TODO: should be cached
    public function defaultToken($key, $type = 'data', $format = 'html') {
        if ($this->default_tokens == null) {
            $data = file_get_contents($this->configFilePath('tokens.json'));
            $this->default_tokens = json_decode($data, true);
        }

        if (!isset($this->default_tokens[$type]))
            return null;

        if (!isset($this->default_tokens[$type][$format]))
            return null;

        if (!isset($this->default_tokens[$type][$format][$key]))
            return null;

        return $this->default_tokens[$type][$format][$key];
    }

    public function contextRules() {
        return array(
            "number" => array(
                "variables" => array()                      // if mapping is not setup, use the actual object as value
            ),
            "gender" => array(
                "variables" => array(
                    "@gender" => "gender",                // string means attribute of an object
//                    "@gender" => function($obj) {
//                        return $obj->gender;
//                    }
                )
            ),
            "genders" => array(
                "variables" => array(
                    "@genders" => function($list){
                        $genders = array();
                        foreach($list as $obj) {
                           array_push($genders, $obj->gender());
                        }
                        return $genders;
                    },
                    "@size" => function($list){
                        return count($list);
                    }
                )
            ),
            "date" => array(
                "variables" => array(
                )
            ),
            "time" => array(
                "variables" => array(
                )
            ),
            "list" => array(
                "variables" => array(
                    "@count" => function($list){
                        return count($list);
                    }
              )
          )
        );
    }

    public function supportedGenders() {
        return array("male", "female", "unknown", "neutral");
    }

    protected function base64UrlDecode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    protected function base64UrlEncode($input) {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);
        return $str;
    }

    public function decodeAndVerifyParams($signed_request, $secret) {
//        \Tr8n\Logger::instance()->info($signed_request);
        $signed_request = urldecode($signed_request);
        $signed_request = base64_decode($signed_request);

        $parts = explode('.', $signed_request);
        $payload_encoded_sig = trim($parts[0], "\n");
        $payload_json_encoded = $parts[1];

        $verification_sig = hash_hmac('sha256', $payload_json_encoded , $secret, true);
        $verification_sig = trim(base64_encode($verification_sig), "\n");

        if ($payload_encoded_sig != $verification_sig) {
            throw new Tr8nException("Invalid signature provided.");
        }

//        \Tr8n\Logger::instance()->info("Signature1", $payload_encoded_sig);
//        \Tr8n\Logger::instance()->info("Signature2", $verification_sig);

        $payload_json = base64_decode($payload_json_encoded);
        $data = json_decode($payload_json, true);
//        \Tr8n\Logger::instance()->info("Data", $data);
        return $data;
    }
}
