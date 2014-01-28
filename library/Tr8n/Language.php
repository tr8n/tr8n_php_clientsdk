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

require_once 'Base.php';
require_once 'TranslationKey.php';

class Language extends Base {

    /**
     * @var Application
     */
    public $application;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $english_name;

    /**
     * @var string
     */
    public $native_name;

    /**
     * @var boolean
     */
    public $right_to_left;

    /**
     * @var LanguageContext[]
     */
    public $contexts;

    /**
     * @var LanguageCase[]
     */
    public $cases;

    /**
     * @var string
     */
    public $flag_url;

    /**
     * @var string
     */
    public $direction;

    /**
     * @param array $attributes
     */
    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->direction = $this->right_to_left ? "rtl" : "ltr";

        $this->contexts = array();
        if (isset($attributes['contexts'])) {
            foreach($attributes['contexts'] as $key => $context) {
                $this->contexts[$key] = new LanguageContext(array_merge($context, array("language" => $this)));
            }
        }

        $this->cases = array();
        if (isset($attributes['cases'])) {
            foreach($attributes['cases'] as $key => $case) {
                $this->cases[$key] = new LanguageCase(array_merge($case, array("language" => $this)));
            }
        }
    }

    /**
     * @param string $locale
     * @return string
     */
    public static function cacheKey($locale) {
        return "l@_[" . $locale . "]";
    }

    /**
     * @param string $keyword
     * @return null|LanguageContext
     */
    public function contextByKeyword($keyword) {
        if (isset($this->contexts[$keyword]))
            return $this->contexts[$keyword];
        return null;
    }

    /**
     * @param string $token_name
     * @return null|LanguageContext
     */
    public function contextByTokenName($token_name) {
        foreach($this->contexts as $key => $ctx) {
            if ($ctx->isAppliedToToken($token_name))
                return $ctx;
        }

        return null;
    }

    /**
     * @param string $key
     * @return null|LanguageCase
     */
    public function languageCase($key) {
        if (!array_key_exists($key, $this->cases))
            return null;

        return $this->cases[$key];
    }

    /**
     * @return string
     */
    public function flagUrl() {
        return $this->flag_url;
    }

    /*
     * By default, application fetches only the basic information about language,
     * so it can be displayed in the language selector. When languages are used for translation,
     * they must fetch full definition, including context and case rules.
     *
     * @return bool
     */
    public function hasDefinition() {
        return (count($this->contexts)>0);
    }

    /**
     * @return bool
     */
    public function isDefault() {
        if ($this->application == null) return true;
        return ($this->application->default_locale == $this->locale);
    }

    /**
     * @return string
     */
    public function direction() {
        return $this->right_to_left ? "rtl" : "ltr";
    }

    /**
     * @param $default
     * @return string
     */
    public function alignment($default) {
        if ($this->right_to_left) return $default;
        return ($default == "left") ? "right" : "left";
    }

    /**
     * @param string $label
     * @param string $description
     * @param array $token_values
     * @param array $options
     * @return string
     */
    public function translate($label, $description = "", $token_values = array(), $options = array()) {
        try {
            $locale = isset($options["locale"]) ? $options["locale"] : Config::instance()->blockOption("locale");
            if ($locale == null) $locale = Config::instance()->default_locale;

            $level = isset($options["level"]) ? $options["level"] : Config::instance()->blockOption("level");
            if ($level == null) $level = Config::instance()->default_level;

            $temp_key = new TranslationKey(array(
                "application"   => $this->application,
                "label"         => $label,
                "description"   => $description,
                "locale"        => $locale,
                "level"         => $level,
                "translations"  => array()
            ));

            if (Config::instance()->isDisabled() || $this->isDefault()) {
                return $temp_key->substituteTokens($label, $token_values, $this, $options);
            }

            $token_values = array_merge($token_values, array("viewing_user" => Config::instance()->current_user));

            // always check request cache first - a page can have the same key appearing multiple times
            // we don't want to hit a remote cache unnecessarily
            $translation_key = $this->application->translationKey($temp_key->key);
            if ($translation_key) {
                return $translation_key->translate($this, $token_values, $options);
            }

            // When translator hasn't enabled inline translations, use cache
            if (Config::instance()->isCacheEnabled()) {
                return $this->translateFromCache($temp_key, $token_values, $options);
            }

            return $this->translateFromService($temp_key, $token_values, $options);
        } catch(Exception $e) {
            \Tr8n\Logger::instance()->error("Failed to translate: " . $label);
            return $label;
        }
	}

    /**
     * @param array $options
     * @return null|Source
     */
    public function currentSource($options = array()) {
        $source_key = isset($options['source']) ? $options["source"] : Config::instance()->blockOption('source');
        if ($source_key == null) $source_key = Config::instance()->current_source;
        return $source_key;
    }

    /**
     * @param TranslationKey $translation_key
     * @param array $token_values
     * @param array $options
     * @return string
     */
    public function translateFromService($translation_key, $token_values = array(), $options = array()) {
        $source_key = $this->currentSource($options);

        if ($source_key) {
            $source = $this->application->source($source_key);

            $source_translation_keys = $source->fetchTranslationsForLanguage($this, $options);

            if (isset($source_translation_keys[$translation_key->key])) {
                $translation_key = $source_translation_keys[$translation_key->key];
            } else {
                $this->application->registerMissingKey($translation_key, $source);
            }
            return $translation_key->translate($this, $token_values, $options);
        }

        $cached_translation_key = $this->application->translationKey($translation_key->key);
        if ($cached_translation_key == null) {
            $translation_key = $translation_key->fetchTranslations($this, $options);
        } else {
            $translation_key = $cached_translation_key;
        }
        return $translation_key->translate($this, $token_values, $options);
    }

    /**
     * @param TranslationKey $translation_key
     * @param array $token_values
     * @param array $options
     * @return string
     */
    public function translateFromCache($translation_key, $token_values = array(), $options = array()) {
        if (Cache::isCachedBySource()) {
            $source_key = $this->currentSource($options);
            $cacheKey = Source::cacheKey($source_key, $this->locale);

            // get cached translation keys for source key
            $source = Cache::fetch($cacheKey);

            if ($source) {
                $translation_keys = $source->translation_keys;
            } else if (Cache::isReadOnly()) {
                $translation_keys = array();
            } else {
                $source = $this->application->source($source_key);
                $translation_keys = $source->fetchTranslationsForLanguage($this, $options);
                Cache::store($cacheKey, $source);
            }

            if (isset($translation_keys[$translation_key->key])) {
                $translation_key = $translation_keys[$translation_key->key];
                return $translation_key->translate($this, $token_values, $options);
            }

            $translation_key->translations = array($this->locale => array());
            $this->application->cacheTranslationKey($translation_key);
            return $translation_key->translate($this, $token_values, $options);
        }

        $cacheKey = TranslationKey::cacheKey($translation_key->label, $translation_key->description, $this->locale);
        $translations = Cache::fetch($cacheKey);

        // cache miss
        if ($translations == null) {
            if (Cache::isReadOnly()) {
                $translation_key->translations = array($this->locale => array());
                $this->application->cacheTranslationKey($translation_key);
                return $translation_key->translate($this, $token_values, $options);
            }

            // fetch and cache key
            $translation_key = $translation_key->fetchTranslations($this, $options);
            $translations = $translation_key->translations($this);
            Cache::store($cacheKey, $translations);
            return $translation_key->translate($this, $token_values, $options);
        }

        // cache hit
        if (!is_array($translations)) {
            $translations = array($translations);
        }
        $translation_key->translations = array($this->locale => $translations);
        $this->application->cacheTranslationKey($translation_key);
        return $translation_key->translate($this, $token_values, $options);
    }

    /**
     * @param array $keys
     * @return array
     */
    public function toArray($keys=array()) {
        if (count($keys) > 0) {
            return parent::toArray($keys);
        }

        $info = parent::toArray(array("locale", "name", "english_name", "native_name", "right_to_left", "flag_url"));
        $info["contexts"] = array();
        foreach($this->contexts as $name=>$value) {
            $info["contexts"][$name] = $value->toArray();
        }
        $info["cases"] = array();
        foreach($this->cases as $name=>$value) {
            $info["cases"][$name] = $value->toArray();
        }
        return $info;
    }

}
