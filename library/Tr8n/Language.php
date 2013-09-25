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

namespace tr8n;

require_once 'Base.php';
require_once 'TranslationKey.php';

class Language extends Base {

    /**
     * @var Application
     */
    public $application;
	public $locale, $name, $english_name, $native_name, $right_to_left, $enabled;
    public $google_key, $facebook_key, $myheritage_key;

    /**
     * @var LanguageContext[]
     */
    public $contexts;

    public $cases;

    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->contexts = array();
        if (isset($attributes['contexts'])) {
            foreach($attributes['contexts'] as $key => $context) {
                $this->contexts[$key] = new \Tr8n\LanguageContext(array_merge($context, array("language" => $this)));
            }
        }

        $this->cases = array();
        if (isset($attributes['cases'])) {
            foreach($attributes['cases'] as $key => $case) {
                $this->cases[$key] = new \Tr8n\LanguageCase(array_merge($case, array("language" => $this)));
            }
        }
    }

    public function contextByKeyword($keyword) {
        if (isset($this->contexts[$keyword]))
            return $this->contexts[$keyword];
        return null;
    }

    public function contextByTokenName($token_name) {
        foreach($this->contexts as $key => $ctx) {
            if ($ctx->isAppliedToToken($token_name))
                return $ctx;
        }

        return null;
    }

    public function languageCase($key) {
        if (!array_key_exists($key, $this->cases))
            return null;

        return $this->cases[$key];
    }

    public function cacheKey($locale) {
        return "language_[" . $locale . "]";
    }

    /*
    By default, application fetches only the basic information about language,
    so it can be displayed in the language selector. When languages are used for translation,
    they must fetch full definition, including context and case rules.
    */
    public function hasDefinition() {
        return (count($this->contexts)>0);
    }

    public function isDefault() {
        if ($this->application == null) return true;
        return (Config::instance()->default_locale == $this->locale);
    }

    public function direction() {
        return $this->right_to_left ? "rtl" : "ltr";
    }

    public function alignment($default) {
        if ($this->right_to_left) return $default;
        return $this->right_to_left ? "right" : "left";
    }

	public function translate($label, $description = "", $token_values = array(), $options = array()) {
        $locale = isset($options["locale"]) ? $options["locale"] : Config::instance()->blockOption("locale");
        if ($locale == null) $locale = Config::instance()->default_locale;

        $level = isset($options["level"]) ? $options["level"] : Config::instance()->blockOption("level");
        if ($level == null) $level = Config::instance()->default_level;

        $source_key = isset($options['source']) ? $options["source"] : Config::instance()->blockOption('source');
        if ($source_key == null) $source_key = Config::instance()->current_source;

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

        $cached_key = null;
        if ($source_key != null) {
            $source = $this->application->source($source_key);
            $source_translation_keys = $source->fetchTranslationsForLanguage($this, $options);

            if (isset($source_translation_keys[$temp_key->key])) {
                $cached_key = $source_translation_keys[$temp_key->key];
            } else {
                $this->application->registerMissingKey($temp_key, $source);
                $cached_key = $temp_key;
            }
        } else {
            $cached_key = $this->application->translationKey($temp_key->key);
            if ($cached_key == null) {
                $cached_key = $temp_key->fetchTranslations($this, $options);
            }
        }

        return $cached_key->translate($this, array_merge($token_values, array("viewing_user" => Config::instance()->current_user)), $options);
	}

    public function flagUrl() {
        return $this->application->host . '/assets/tr8n/flags/' . $this->locale . '.png';
    }
}
