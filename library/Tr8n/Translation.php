<?php

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

?>


