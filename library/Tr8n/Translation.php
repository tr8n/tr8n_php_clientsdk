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

namespace Tr8n;

class Translation extends Base {

    protected $translation_key, $language;
    protected $locale, $label, $context;


    public function setTranslationKey($translation_key) {
        $this->translation_key = $translation_key;
        $this->language = $translation_key->application->language($this->locale);
    }

    public function hasContextRules() {
        return ($this->context != null and count($this->context) > 0);
    }

    /*
     * For translation to be valid, it must match all rules, or have no rules.
     */
    public function isValidTranslation($token_values) {
       if (!$this->hasContextRules())
           return true;

        foreach($this->context as $token_name=>$rules) {
            $token_value = \Tr8n\Tokens\Base::tokenObject($token_values, $token_name);

            if ($token_value === null)
                return false;

            foreach($rules as $rule_assoc) {
                $rule = $this->language->contextRule($rule_assoc['type'], $rule_assoc['key']);
                if (!$rule) return false;
                if (!$rule->evaluate($token_value)) return false;
            }
        }

        return true;
    }
}
