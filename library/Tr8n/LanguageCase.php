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

class LanguageCase extends Base {

    public $keyword, $latin_name, $native_name, $description, $application;
    public $language, $rules;

    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->rules = array();
        if (array_key_exists('rules', $attributes)) {
            foreach($attributes['rules'] as $rule) {
                array_push($this->rules, new \Tr8n\LanguageCaseRule(array_merge($rule, array("language_case" => $this))));
            }
        }
    }

    public function substitutionExpression() {
        return '/<\/?[^>]*>/';
    }

    public function apply($object, $value, $options = array()) {
        $tags = array();
        preg_match_all($this->substitutionExpression(), $value, $tags);
        $tags = array_unique($tags[0]);
        $sanitized_value = preg_replace($this->substitutionExpression(), '', $value);

        $elements = array();
        if ($this->application == 'phrase') {
            $elements = array($sanitized_value);
        } else {
            $elements = array_unique(preg_split('/[\s\/]/', $sanitized_value));
        }

        # replace html tokens with temporary placeholders {$h1}
        for($i=0; $i<count($tags); $i++) {
            $value = str_replace($tags[$i], '{$h' . $i . '}', $value);
        }

        # replace words with temporary placeholders {$w1}
        for($i=0; $i<count($elements); $i++) {
            $value = str_replace($elements[$i], '{$w' . $i . '}', $value);
        }

        $transformed_elements = array();
        foreach($elements as $element) {
            $case_rule = $this->matchRule($object, $element);
            $case_value = ($case_rule == null ? $element : $case_rule->apply($element));
            array_push($transformed_elements, $this->decorate($element, $case_value, $case_rule, $options));
        }

        # replace back temporary placeholders {$w1}
        for($i=0; $i<count($transformed_elements); $i++) {
            $value = str_replace('{$w' . $i . '}', $transformed_elements[$i], $value);
        }

        # replace back temporary placeholders {$h1}
        for($i=0; $i<count($tags); $i++) {
            $value = str_replace('{$h' . $i . '}', $tags[$i], $value);
        }

        return $value;
    }

    public function matchRule($object, $value) {
        foreach($this->rules as $rule) {
            if ($rule->evaluate($object, $value))
                return $rule;
        }
        return null;
    }

    public function decorate($object, $value, $rule, $options = array()) {
        return $value;
    }

}