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

class Translation extends Base {

    public $translation_key, $language;
    public $locale, $label, $context;

    public function __construct($attributes=array()) {
        parent::__construct($attributes);

        if (isset($this->locale)) {
            $this->language = $this->translation_key->application->language($this->locale);
        }
    }

    public function setTranslationKey($translation_key) {
        $this->translation_key = $translation_key;
        $this->language = $translation_key->application->language($this->locale);
    }

    public function hasContextRules() {
        return ($this->context != null and count($this->context) > 0);
    }

    /**
     * For translation to be valid, it must match all rules, or have no rules.
     */
    public function isValidTranslation($token_values) {
       if (!$this->hasContextRules())
           return true;

        foreach($this->context as $token_name=>$rules) {
            $token_object = \Tr8n\Tokens\Base::tokenObject($token_values, $token_name);

            if ($token_object === null)
                return false;

            foreach($rules as $context_key => $rule_key) {
                if ($rule_key == "other") continue; // why?

                $context = $this->language->contextByKeyword($context_key);
                if ($context == null) return false; // unsupported context type

                $rule = $context->findMatchingRule($token_object);
                if ($rule == null || $rule->keyword != $rule_key) return false;
            }
        }

        return true;
    }
}
