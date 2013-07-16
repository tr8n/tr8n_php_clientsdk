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

namespace tr8n;

class Application extends Base {

    public $host, $key, $secret, $name, $description, $definition, $version, $updated_at;
    public $languages, $sources, $components;

    # TODO: move those attributes out - must be cached
    public $languages_by_locale, $sources_by_key, $components_by_key, $translation_keys;

    public static function init($host, $key, $secret, $options = array()) {
        if (!array_key_exists('definition', $options) || $options['definition'] == null)
            $options['definition'] = true;

        Logger::instance()->info("Initializing application...");

        $app = Application::executeRequest("application", array('client_id' => $key, 'definition' => $options['definition']),
                           array('host' => $host, 'client_secret' => $secret, 'class' => 'Tr8n\Application', 'attributes' => array(
                                    'host' => $host,
                                    'key' => $key,
                                    'secret' => $secret)
                           )
        );


        return $app;
    }

    function __construct($attributes=array()) {
        parent::__construct($attributes);

        if (!array_key_exists('definition', $attributes)) {
            $this->definition = array();
        }

        $this->languages = array();
        if (array_key_exists('languages', $attributes)) {
            foreach($attributes['languages'] as $l) {
                array_push($this->languages, new Language(array_merge($l, array("application" => $this))));
            }
        }

        $this->sources = array();
        if (array_key_exists('sources', $attributes)) {
            foreach($attributes['sources'] as $l) {
                array_push($this->sources, new Source(array_merge($l, array("application" => $this))));
            }
        }

        $this->components = array();
        if (array_key_exists('components', $attributes)) {
            foreach($attributes['components'] as $l) {
                array_push($this->components, new Component(array_merge($l, array("application" => $this))));
            }
        }

        $this->languages_by_locale  = null;
        $this->sources_by_key       = null;
        $this->components_by_key    = null;
        $this->translation_keys     = array();
    }

    /*
     * TODO: cache this method
    */
    public function language($locale = null, $fetch = false) {
        $locale = ($locale == null ? Config::instance()->default_locale : $locale);

        if (count($this->languages_by_locale) == null) {
            $this->languages_by_locale = array();
            foreach($this->languages as $lang) {
                $this->languages_by_locale[$lang->locale] = $lang;
            }
        }

        if (array_key_exists($locale, $this->languages_by_locale)) {
            return $this->languages_by_locale[$locale];
        }

        if ($fetch == false) return null;

        $this->languages_by_locale[$locale] = $this->get("language", array("locale" => $locale), array("class" => '\Tr8n\Language', "attributes" => array("application" => $this)));
        return $this->languages_by_locale[$locale];
    }

    public function addLanguage($language) {
        $lang = $this->language($language->locale, false);
        if ($lang != null) return $lang;

        $language->application = $this;
        array_push($this->languages, $language);
        $this->languages_by_locale[$language->locale] = $language;

        return $language;
    }

    public function defaultToken($key, $type = "data") {
        if (!array_key_exists($key, $this->definition["default_".$type."_tokens"]))
            return null;
        return $this->definition["default_".$type."_tokens"][$key];
    }


    public function source($key) {

    }

    public function component($key) {

    }

    /*
     * TODO: cache this method
     */
    public function translationKey($key) {
        if (!array_key_exists($key, $this->translation_keys))
            return null;
        return $this->translation_keys[$key];
    }

    public function cacheTranslationKey($translation_key) {
        $cached_key = $this->translationKey($translation_key->key);
        if ($cached_key) {
            # move translations from tkey to the cached key
            foreach($translation_key->translations as $locale => $translations) {
                $cached_key->setLanguageTranslations($this->language($locale), $translations);
            }
            return $cached_key;
        }

        $translation_key->setApplication($this);
        $this->translation_keys[$translation_key->key] = $translation_key;
        return $translation_key;
    }

    /*
     *
     * API Related methods
     *
     */
    public function get($path, $params = array(), $options = array()) {
        return $this->api($path, $params, $options);
    }

    public function post($path, $params = array(), $options = array()) {
        $options["method"] = 'POST';
        return $this->api($path, $params, $options);
    }

    public function api($path, $params = array(), $options = array()) {
        $options["host"] = $this->host;
        $params["client_id"] = $this->key;
        $params["t"] = microtime(true);

        return self::executeRequest($path, $params, $options);
    }

}
