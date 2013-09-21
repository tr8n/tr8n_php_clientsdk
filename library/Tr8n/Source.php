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

    public $application, $source, $url, $name, $description;
    public $translation_keys;  // hashed by key

	function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->translation_keys = null;
	}

    public function fetchTranslationsForLanguage($language, $options = array()) {
        # for current translators who use inline mode - always fetch translations
        if (\Tr8n\Config::instance()->current_translator && \Tr8n\Config::instance()->current_translator->isInlineModeEnabled()) {

        }

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

}
