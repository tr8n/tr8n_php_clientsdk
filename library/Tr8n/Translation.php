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

class Translation extends Base {

    /**
     * @var TranslationKey
     */
    public $translation_key;

    /**
     * @var Language
     */
    public $language;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var string
     */
    public $label;

    /**
     * @var array[]
     */
    public $context;

    /**
     * @var integer
     */
    public $precedence;

    /**
     * @param array $attributes
     */
    public function __construct($attributes=array()) {
        parent::__construct($attributes);

        if (isset($this->locale)) {
            $this->language = $this->translation_key->application->language($this->locale);
        }

        $this->calculatePrecedence();
    }

    /**
     * @param $translation_key
     */
    public function setTranslationKey($translation_key) {
        $this->translation_key = $translation_key;
        $this->language = $translation_key->application->language($this->locale);
    }

    /**
     * @return bool
     */
    public function hasContextRules() {
        return ($this->context != null and count($this->context) > 0);
    }

    /**
     * the precedence is based on the number of fallback rules in the context.
     * a fallback rule is indicated by the keyword "other"
     * the more "others" are used the lower the precedence will be
     *
     * 0 indicates the highest precedence
     */
    public function calculatePrecedence() {
        $this->precedence = 0;

        if (!$this->hasContextRules()) {
            return;
        }

        foreach($this->context as $token_name=>$rules) {
            foreach($rules as $context_key=>$rule_key) {
                if ($rule_key == "other")
                    $this->precedence += 1;
            }
        }
    }

    /**
     * @param $token_values
     * @return bool
     */
    public function isValidTranslation($token_values) {
       if (!$this->hasContextRules())
           return true;

        foreach($this->context as $token_name=>$rules) {
            $token_object = \Tr8n\Tokens\DataToken::tokenObject($token_values, $token_name);

            if ($token_object === null)
                return false;

            foreach($rules as $context_key=>$rule_key) {
                if ($rule_key == "other") continue;

                $context = $this->language->contextByKeyword($context_key);
                if ($context == null) return false; // unsupported context type

                $rule = $context->findMatchingRule($token_object);
                if ($rule == null || $rule->keyword != $rule_key) return false;
            }
        }

        return true;
    }

}
