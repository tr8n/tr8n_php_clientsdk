<?php

/**
 * Copyright (c) 2014 Michael Berkovich, TranslationExchange.com
 *
 *  _______                  _       _   _             ______          _
 * |__   __|                | |     | | (_)           |  ____|        | |
 *    | |_ __ __ _ _ __  ___| | __ _| |_ _  ___  _ __ | |__  __  _____| |__   __ _ _ __   __ _  ___
 *    | | '__/ _` | '_ \/ __| |/ _` | __| |/ _ \| '_ \|  __| \ \/ / __| '_ \ / _` | '_ \ / _` |/ _ \
 *    | | | | (_| | | | \__ \ | (_| | |_| | (_) | | | | |____ >  < (__| | | | (_| | | | | (_| |  __/
 *    |_|_|  \__,_|_| |_|___/_|\__,_|\__|_|\___/|_| |_|______/_/\_\___|_| |_|\__,_|_| |_|\__, |\___|
 *                                                                                        __/ |
 *                                                                                       |___/
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

class Application extends Base {
    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $secret;
    /**
     * @var string
     */
    public $name;

    /**
     * @vars string
     */
    public $default_locale;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $threshold;

    /**
     * @var int
     */
    public $translator_level;

    /**
     * @var boolean[]
     */
    public $features;

    /**
     * @var string[]
     */
    public $shortcuts;

    /**
     * @var string
     */
    public $css;

    /**
     * @var Language[]
     */
    public $languages;

    /**
     * @var Source[]
     */
    public $sources;

    /**
     * @var Component[]
     */
    public $components;

    /**
     * @var Boolean
     */
    public $initialized;

    /**
     * @var Language[]
     */
    public $languages_by_locale;

    /**
     * @var Source[]
     */
    public $sources_by_key;

    /**
     * @var Component[]
     */
    public $components_by_key;

    /**
     * @var TranslationKey[]
     */
    public $translation_keys;

    /**
     * @var TranslationKey[]
     */
    public $missing_keys_by_sources;

    /**
     * @var ApiClient
     */
    private $api_client;

    /**
     * @param string $host
     * @param string $key
     * @param string $secret
     * @param array $options
     * @return Application
     */
    public static function init($host, $key, $secret, $options = array()) {
        if (!array_key_exists('definition', $options) || $options['definition'] == null)
            $options['definition'] = true;

        Logger::instance()->info("Initializing application...");

        $app = ApiClient::fetch("application", array('client_id' => $key, 'definition' => $options['definition']),
            array('host' => $host, 'client_secret' => $secret, 'class' => '\Tr8n\Application', 'cache_key' => self::cacheKey())
        );

        $app->key = $key;
        $app->host = $host;
        $app->secret = $secret;
        $app->initialized = true;

        return $app;
    }

    /**
     * @return Application
     */
    public static function dummyApplication() {
        Logger::instance()->info("Falling back onto dummy application...");

        $app = new Application();
        $app->name = "Disconnected Application";
        $app->languages_by_locale = array(
            \Tr8n\Config::instance()->default_locale => \Tr8n\Config::instance()->defaultLanguage()
        );
        return $app;
    }

    /**
     * @param array $attributes
     */
    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->languages = array();
        if (isset($attributes['languages'])) {
            foreach($attributes['languages'] as $l) {
                array_push($this->languages, new Language(array_merge($l, array("application" => $this))));
            }
        }

        $this->sources = array();
        if (isset($attributes['sources'])) {
            foreach($attributes['sources'] as $l) {
                array_push($this->sources, new Source(array_merge($l, array("application" => $this))));
            }
        }

        $this->components = array();
        if (isset($attributes['components'])) {
            foreach($attributes['components'] as $l) {
                array_push($this->components, new Component(array_merge($l, array("application" => $this))));
            }
        }

        if (isset($attributes['features'])) {
            $this->features = $attributes['features'];
        }

        if (isset($attributes['shortcuts'])) {
            $this->shortcuts = $attributes['shortcuts'];
        }

        if (isset($attributes['css'])) {
            $this->css = $attributes['css'];
        }

        $this->languages_by_locale  = null;
        $this->sources_by_key       = null;
        $this->components_by_key    = null;
        $this->translation_keys     = array();
        $this->missing_keys_by_sources = null;
    }

    /**
     * @param string $key
     * @return string
     */
    public static function cacheKey() {
        return "application";
    }

    /**
     * @param string|null $locale
     * @param bool $fetch
     * @return Language
     */
    public function language($locale = null) {
        $locale = ($locale == null ? Config::instance()->default_locale : $locale);

        if ($this->languages_by_locale == null) {
            $this->languages_by_locale = array();
        }

        if (isset($this->languages_by_locale[$locale])) {
            return $this->languages_by_locale[$locale];
        }

        $language = $this->apiClient()->get("language",
            array("locale" => $locale),
            array("class" => '\Tr8n\Language',
                  "attributes" => array("application" => $this),
                  "cache_key"  => Language::cacheKey($locale)
            )
        );

        $language->application = $this;
        $this->languages_by_locale[$locale] = $language;
        return $this->languages_by_locale[$locale];
    }

    /**
     * @param Language $language
     * @return Language
     */
    public function addLanguage($language) {
        if (isset($this->languages_by_locale[$language->locale])) {
            return $this->languages_by_locale[$language->locale];
        }

        $language->application = $this;
        array_push($this->languages, $language);
        $this->languages_by_locale[$language->locale] = $language;

        return $language;
    }

    /**
     * @param string $key
     * @param bool $register
     * @return null|Source
     */
    public function source($key, $locale) {
        if ($this->sources_by_key == null) {
            $this->sources_by_key = array();
            foreach($this->sources as $source) {
                $this->sources_by_key[$source->source] = $source;
            }
        }

        if (isset($this->sources_by_key[$key])) {
            return $this->sources_by_key[$key];
        }

        try {
            $this->sources_by_key[$key] = $this->apiClient()->get("source",
                array("source" => $key, "locale" => $locale, "translations" => "true"),
                array("class" => '\Tr8n\Source',
                    "attributes" => array("application" => $this),
                    "cache_key" => Source::cacheKey($key, $locale)
                )
            );
        } catch (Tr8nException $e) {
            $this->sources_by_key[$key] = new Source(array("source" => $key));
        }

        return $this->sources_by_key[$key];
    }

    /**
     * @param string $key
     * @param bool $register
     * @return null|Component
     */
    public function component($key, $register = true) {
        if ($this->components_by_key == null) {
            $this->components_by_key = array();
            foreach($this->components as $component) {
                $this->components_by_key[$component->key] = $component;
            }
        }

        if (isset($this->components_by_key[$key])) {
            return $this->components_by_key[$key];
        }

        if ($register != true) return null;

        $this->components_by_key[$key] = $this->apiClient()->post("component/register",
            array("components_by_key" => $key),
            array("class" => '\Tr8n\Component', "attributes" => array("application" => $this))
        );

        return $this->components_by_key[$key];
    }

    /**
     * @param string $key
     * @return null|TranslationKey
     */
    public function translationKey($key) {
        if (!isset($this->translation_keys[$key])) return null;
        return $this->translation_keys[$key];
    }

    /**
     * @param TranslationKey $translation_key
     * @return null|TranslationKey
     */
    public function cacheTranslationKey($translation_key) {
        $cached_key = $this->translationKey($translation_key->key);
        if ($cached_key !== null) {
            # move translations from tkey to the cached key
            foreach($translation_key->translations as $locale => $translations) {
                $language = $this->language($locale);
                $cached_key->setLanguageTranslations($language, $translations);
            }
            return $cached_key;
        }

        $translation_key->setApplication($this);
        $this->translation_keys[$translation_key->key] = $translation_key;
        return $translation_key;
    }

    /**
     * @param TranslationKey $translation_key
     * @param Source $source
     */
    public function registerMissingKey($translation_key, $source_key = 'undefined') {
        if (Cache::isReadOnly() && !(Config::instance()->current_translator && Config::instance()->current_translator->isInlineModeEnabled()))
            return;

        if ($this->missing_keys_by_sources === null) {
            $this->missing_keys_by_sources = array();
        }

        if (!isset($this->missing_keys_by_sources[$source_key])) {
            $this->missing_keys_by_sources[$source_key] = array();
        }

        if (!isset($this->missing_keys_by_sources[$source_key][$translation_key->key])) {
            $this->missing_keys_by_sources[$source_key][$translation_key->key] = $translation_key;
        }
    }

    /**
     * Submits missing keys to the service
     */
    public function submitMissingKeys() {
        if ($this->missing_keys_by_sources == null)
            return;

        $params = array();
        $source_keys = array();
        foreach($this->missing_keys_by_sources as $source => $keys) {
            array_push($source_keys, $source);

            $keys_data = array();
            foreach($keys as $key) {
                /** @var $key TranslationKey */
                $json = array(
                    "key"           => $key->key,
                    "label"         => $key->label,
                    "description"   => $key->description,
                    "locale"        => $key->locale,
                    "level"         => $key->level
                );
                array_push($keys_data, $json);
            }
            array_push($params, array("source" => $source, "keys" => $keys_data));
        }

        $params = \Tr8n\Utils\ArrayUtils::trim($params);
        $this->apiClient()->post('source/register_keys', array("source_keys" => json_encode($params)));
        $this->missing_keys_by_sources = null;

        // All source caches must be reset for all languages, since the keys have changed
        foreach ($this->languages_by_locale as $locale => $language) {
            foreach ($source_keys as $source_key) {
                Cache::delete(Source::cacheKey($source_key, $locale));
            }
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function isFeatureEnabled($key) {
        if (!$this->features || !isset($this->features[$key]))
            return false;
        return $this->features[$key];
    }

    /**
     * @return \Tr8n\EmailTemplate[]
     */
    public function emailTemplates() {
        return $this->apiClient()->get("email/templates",
            array(),
            array("class" => '\Tr8n\EmailTemplate', "attributes" => array("application" => $this))
        );
    }

    /**
     * @return string
     */
    public function jsBootUrl() {
        return $this->host . "/tr8n/api/proxy/boot.js?client_id=" . $this->key;
    }

    /**
     * @param array $keys
     * @return array
     */
    public function toArray($keys=array()) {
        $hash = parent::toArray(array("key", "host", "name", "default_locale", "threshold", "translator_level", "features", "shortcuts", "css", "languages", "description"));
        $hash["languages"] = array();
        foreach($this->languages as $l) {
            array_push($hash["languages"], $l->toArray(array("locale", "name", "english_name", "native_name", "right_to_left", "flag_url")));
        }
        return $hash;
    }


    public function apiClient() {
        if ($this->api_client == null) {
            $this->api_client = new ApiClient($this);
        }
        return $this->api_client;
    }
}
