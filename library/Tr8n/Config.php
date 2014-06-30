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
    public $requested_sources;

    /**
     * @var array
     */
    private $block_options;

    /**
     * @param $config
     */
    public static function init($config) {
        static $inst = null;
        $inst = $config;
    }

    /**
     * @return Config
     */
    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new Config();
        }
        return $inst;
    }

    /**
     *
     */
    function __construct() {
        $this->application = null;
        $this->default_locale = 'en-US';
        $this->default_level = 0;
        $this->block_options = array();
    }

    /**
     * @param $configurator
     */
    public static function configure($configurator) {
        $configurator(self::instance());
    }

    /**
     * @param $key
     * @return mixed|null|string|\string[]
     */
    public function configValue($key, $default = null) {
        if ($this->config == null) {
            $data = file_get_contents($this->configFilePath('config.json'));
            $this->config = json_decode($data, true);
        }

        $value = $this->config;
        $parts = explode(".", $key);
        foreach($parts as $part) {
            if (!isset($value[$part])) return $default;
            $value = $value[$part];
        }

        return $value;
    }

    /**
     *
     */
    public function updateConfig() {
        file_put_contents($this->configFilePath('config.json'), StringUtils::prettyPrint(json_encode($this->config)));
    }

    /**
     * @param null $host
     * @param null $client_id
     * @param null $client_secret
     * @return null|Application
     */
    public function initApplication($client_id = null, $client_secret = null, $host = null) {
        if ($client_id == null) { // fallback onto the configuration file
            $client_id = $this->configValue("application.key");
            $client_secret = $this->configValue("application.secret");
            $host = $this->configValue("application.host", "https://translationexchange.com");
        }

        if ($this->application == null) {
            try {
                $this->application = Application::init($host, $client_id, $client_secret);
            } catch (Tr8nException $e) {
                $this->application = Application::dummyApplication();
            }
        }
        return $this->application;
    }

    /**
     * @param array $options
     */
    public function initRequest($options = array()) {
        if ($this->isEnabled()) {
            $this->current_language = $this->application->language((isset($options['locale']) ? $options['locale'] : $this->default_locale), true);
        } else {
            $this->current_language = $this->application->language($this->default_locale);
        }

        $this->current_translator = (isset($options['translator']) ? $options['translator'] : null);
        $this->current_source = (isset($options['source']) ? $options['source'] : null);
        $this->current_component = (isset($options['component']) ? $options['component'] : null);
        $this->requested_sources = array();
        if ($this->current_source) array_push($this->requested_sources, $this->current_source);
    }

    /**
     * @param array $options
     */
    public function completeRequest($options = array()) {
        if (!isset($this->application)) return;
        $this->application->submitMissingKeys();
    }

    /**
     * @param array $options
     */
    public function beginBlockWithOptions($options = array()) {
        array_push($this->block_options, $options);

        if (isset($options["source"]))
            array_push($this->requested_sources, $options["source"]);
    }

    /**
     * @param $key
     * @return null
     */
    public function blockOption($key) {
        if (count($this->block_options) == 0) return null;
        $current_options = $this->block_options[0];
        if (!array_key_exists($key, $current_options)) return null;
        return $current_options[$key];
    }

    /**
     * @return null
     */
    public function finishBlockWithOptions() {
        if (count($this->block_options) == 0) return null;
        array_pop($this->block_options);
    }

    /**
     * @return bool
     */
    public function isEnabled() {
        return ($this->application != null && $this->application->initialized);
    }

    /**
     * @return bool
     */
    public function isDisabled() {
        return !self::isEnabled();
    }

    /**
     * @return mixed|null|string|\string[]
     */
    public function isLoggerEnabled() {
        return $this->configValue("log.enabled");
    }

    /**
     * @return string
     */
    public function loggerFilePath() {
        return __DIR__."/../../log/tr8n.log";
    }

    /**
     * @return string
     */
    public function cachePath() {
        return __DIR__."/../../cache/";
    }

    /**
     * @return int|mixed|null|string|\string[]
     */
    public function cacheVersion() {
        $version = $this->configValue("cache.version");
        return ($version == null ? 0 : $version);
    }

    /**
     * @return int|mixed|null|string|\string[]
     */
    public function cacheTimeout() {
        $timeout = $this->configValue("cache.timeout");
        return ($timeout == null ? 3600 : $timeout);
    }

    /**
     *
     */
    public function incrementCache() {
        $version = $this->cacheVersion();
        $this->config["cache"]["version"] =  $version + 1;
        $this->updateConfig();
    }

    /**
     * @return int
     */
    public function loggerSeverity() {
        $severity = $this->configValue("log.severity");
        if ($severity == null)
            $severity = "debug";

        if ($severity == "error")
            return Logger::ERROR;

        if ($severity == "warning")
            return Logger::WARNING;

        if ($severity == "notice")
            return Logger::NOTICE;

        if ($severity == "info")
            return Logger::INFO;

        return Logger::DEBUG;
    }

    /**
     * @return bool
     */
    public function isCacheEnabled() {
        if ($this->configValue("cache.enabled") === false) {
            return false;
        }

        if ($this->current_translator && $this->current_translator->isInlineModeEnabled())
            return false;

        return true;
    }

    /**
     * @return null|string
     */
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

    /**
     * @return string
     */
    public function decoratorClass() {
        $decorator = $this->configValue("decorator");
        if ($decorator == null)
            $decorator = "html";

        return '\Tr8n\Decorators\HtmlDecorator';
    }

    /**
     * @return mixed
     */
    public function defaultSource() {
        return $_SERVER["REQUEST_URI"];
    }

    /**
     * @return Language
     */
    public function defaultLanguage() {
        $data = file_get_contents($this->configFilePath('languages/' . $this->default_locale . '.json'));
        return new \Tr8n\Language(json_decode($data, true));
    }

    /**
     * @param $file_name
     * @return string
     */
    public function configFilePath($file_name) {
        return __DIR__."/../../config/" . $file_name;
    }

    /**
     * @param string $key
     * @param string $type
     * @param string $format
     * @return null
     */
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


    /**
     * @param string $key
     * @param string $value
     * @param string $type
     * @param string $format
     * @return string
     */
    public function setDefaultToken($key, $value, $type = 'data', $format = 'html') {
        if ($this->default_tokens == null) {
            $data = file_get_contents($this->configFilePath('tokens.json'));
            $this->default_tokens = json_decode($data, true);
        }

        if (!isset($this->default_tokens[$type])) {
            $this->default_tokens[$type] = array();
        }

        if (!isset($this->default_tokens[$type][$format])) {
            $this->default_tokens[$type][$format] = array();
        }

        $this->default_tokens[$type][$format][$key] = $value;

        return $this->default_tokens[$type][$format][$key];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function supportedGenders() {
        return array("male", "female", "unknown", "neutral");
    }

    /**
     * @param $input
     * @return string
     */
    protected function base64UrlDecode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * @param $input
     * @return mixed|string
     */
    protected function base64UrlEncode($input) {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);
        return $str;
    }

    /**
     * @param $signed_request
     * @param $secret
     * @return mixed
     * @throws Tr8nException
     */
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
