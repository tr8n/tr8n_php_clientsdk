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

        $app = Cache::fetch(self::cacheKey($key));
        if ($app == null) {
            $app = Application::executeRequest("application", array('client_id' => $key, 'definition' => $options['definition']),
                array('host' => $host, 'client_secret' => $secret, 'class' => 'Tr8n\Application')
            );
            Cache::store(self::cacheKey($key), $app);
        }

        $app->key = $key;
        $app->host = $host;
        $app->secret = $secret;

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
    public static function cacheKey($key) {
        return "a@_[" . $key . "]";
    }

    /**
     * @param string|null $locale
     * @param bool $fetch
     * @return Language
     */
    public function language($locale = null, $fetch = true) {
        $locale = ($locale == null ? Config::instance()->default_locale : $locale);

        if ($this->languages_by_locale == null) {
            $this->languages_by_locale = array();
        }

        if (isset($this->languages_by_locale[$locale])) {
            return $this->languages_by_locale[$locale];
        }

        $language = Cache::fetch(Language::cacheKey($locale));
        /** @var $language Language */
        if ($language) {
            $language->application = $this;
            $this->languages_by_locale[$locale] = $language;
            return $language;
        }

        if ($fetch == false) return null;

        $this->languages_by_locale[$locale] = $this->get("language", array("locale" => $locale), array("class" => '\Tr8n\Language', "attributes" => array("application" => $this)));

        if (Config::instance()->isCacheEnabled() && !Cache::isReadOnly()) {
            Cache::store(Language::cacheKey($locale), $this->languages_by_locale[$locale]);
        }

        return $this->languages_by_locale[$locale];
    }

    /**
     * @param Language $language
     * @return Language
     */
    public function addLanguage($language) {
        $lang = $this->language($language->locale, false);
        if ($lang != null) return $lang;

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
    public function source($key, $register = true) {
        if ($this->sources_by_key == null) {
            $this->sources_by_key = array();
            foreach($this->sources as $source) {
                $this->sources_by_key[$source->source] = $source;
            }
        }

        if (isset($this->sources_by_key[$key])) {
            return $this->sources_by_key[$key];
        }

        if ($register != true) return null;

        $this->sources_by_key[$key] = $this->post("source/register", array("source" => $key), array("class" => '\Tr8n\Source', "attributes" => array("application" => $this)));
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

        $this->components_by_key[$key] = $this->post("component/register", array("components_by_key" => $key), array("class" => '\Tr8n\Component', "attributes" => array("application" => $this)));
        return $this->components_by_key[$key];
    }

    /**
     * @param string $key
     * @return null|TranslationKey
     */
    public function translationKey($key) {
        if (!array_key_exists($key, $this->translation_keys))
            return null;
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
    public function registerMissingKey($translation_key, $source) {
        if ($this->missing_keys_by_sources === null) {
            $this->missing_keys_by_sources = array();
        }

        if (!isset($this->missing_keys_by_sources[$source->source])) {
            $this->missing_keys_by_sources[$source->source] = array();
        }

        if (!isset($this->missing_keys_by_sources[$source->source][$translation_key->key])) {
            $this->missing_keys_by_sources[$source->source][$translation_key->key] = $translation_key;
        }
    }

    /**
     *
     */
    public function submitMissingKeys() {
        if ($this->missing_keys_by_sources == null)
            return;

        $params = array();
        foreach($this->missing_keys_by_sources as $source => $keys) {
            $keys_data = array();
            foreach($keys as $key) {
                /** @var $key TranslationKey */
                $json = $key->toArray();
                array_push($keys_data, $json);
            }
            array_push($params, array("source" => $source, "keys" => $keys_data));
        }

        $params = \Tr8n\Utils\ArrayUtils::trim($params);
        $this->post('source/register_keys', array("source_keys" => json_encode($params)));
        $this->missing_keys_by_sources = null;
    }


    /**
     * @return \Tr8n\EmailTemplate[]
     */
    public function emailTemplates() {
        return $this->get("email/templates", array(), array("class" => '\Tr8n\EmailTemplate', "attributes" => array("application" => $this)));
    }

    /*
     * @param string $path
     * @param array $params
     * @param array $options
     * @return array
     */
    public function get($path, $params = array(), $options = array()) {
        return $this->api($path, $params, $options);
    }

    /**
     * @param string $path
     * @param array $params
     * @param array $options
     * @return array
     */
    public function post($path, $params = array(), $options = array()) {
        $options["method"] = 'POST';
        return $this->api($path, $params, $options);
    }

    /**
     * @param string $path
     * @param array $params
     * @param array $options
     * @return array
     */
    public function api($path, $params = array(), $options = array()) {
        $options["host"] = $this->host;
        $params["client_id"] = $this->key;
        $params["t"] = microtime(true);

        return self::executeRequest($path, $params, $options);
    }

    /**
     * @return string
     */
    public function jsBootUrl() {
        return $this->host . "/tr8n/api/proxy/boot.js?client_id=" . $this->key;
    }

    public function toArray($keys=array()) {
        return parent::toArray(array("host"));
    }
}
