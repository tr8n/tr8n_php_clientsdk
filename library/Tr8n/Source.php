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

class Source extends Base {

    /**
     * @var Application
     */
    public $application;

    /**
     * @var string
     */
    public $source;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var TranslationKey[]
     */
    public $translation_keys;  // hashed by key

    /**
     * @param array $attributes
     */
    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->translation_keys = null;
        if (isset($attributes['translation_keys'])) {
            $this->translation_keys = array();
            foreach($attributes['translation_keys'] as $tk) {
                $translation_key = new TranslationKey(array_merge($tk, array("application" => $this->application)));
                $this->translation_keys[$translation_key->key] = $this->application->cacheTranslationKey($translation_key);
            }
        }
	}

    /**
     * @param string $source_key
     * @param string $locale
     * @return string
     */
    public static function cacheKey($source_key, $locale) {
        return "s@_[" . $locale . "]_[" . $source_key . "]";
    }

    /**
     * @param Language $language
     * @param array $options
     * @return array|null
     */
    public function fetchTranslationsForLanguage($language, $options = array()) {
        if ($this->translation_keys !== null) {
            return $this->translation_keys;
        }

        $keys_with_translations = $this->application->get("source/translations", array("source" => $this->source, "locale" => $language->locale),
                            array("class" => '\Tr8n\TranslationKey', "attributes" => array("application" => $this->application)));

        $this->translation_keys = array();

        foreach($keys_with_translations as $translation_key) {
            $this->translation_keys[$translation_key->key] = $this->application->cacheTranslationKey($translation_key);
        }

        return $this->translation_keys;
    }

    public function toArray($keys=array()) {
        $info = parent::toArray(array("source", "url", "name", "description"));
        if ($this->translation_keys) {
            $info["translation_keys"] = array();
            foreach(array_values($this->translation_keys) as $tkey) {
                /** @var TranslationKey $tkey */
                array_push($info["translation_keys"], $tkey->toArray());
            }
        }
        return $info;
    }

}
