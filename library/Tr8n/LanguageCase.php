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

class LanguageCase extends Base {

    /**
     * @var Application
     */
    public $application;

    /**
     * @var Language
     */
    public $language;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $keyword;

    /**
     * @var string
     */
    public $latin_name;

    /**
     * @var string;
     */
    public $native_name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var LanguageCaseRule[]
     */
    public $rules;

    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->rules = array();
        if (isset($attributes['rules'])) {
            foreach($attributes['rules'] as $rule) {
                array_push($this->rules, new LanguageCaseRule(array_merge($rule, array("language_case" => $this))));
            }
        }
    }

    public function substitutionExpression() {
        return '/<\/?[^>]*>/';
    }

    public function findMatchingRule($value, $object = null) {
        foreach($this->rules as $rule) {
            if ($rule->evaluate($value, $object) == true)
                return $rule;
        }

        return null;
    }

    public function apply($value, $object = null, $options = array()) {
        $tags = array();
        preg_match_all($this->substitutionExpression(), $value, $tags);
        $tags = array_unique($tags[0]);
        $sanitized_value = preg_replace($this->substitutionExpression(), '', $value);

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
            $rule = $this->findMatchingRule($element, $object);
            $case_value = ($rule==null ? $element : $rule->apply($element));
            array_push($transformed_elements, $this->decorate($element, $case_value, $rule, $options));
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

    public function decorate($element, $value, $rule, $options = array()) {
        if (isset($options["skip_decoration"])) return $value;
        if ($this->language->isDefault()) return $value;

        $config = Config::instance();
        if ($config->current_translator == null) return $value;
        if (!$config->current_translator->isInlineModeEnabled()) return $value;

        return "<span class='tr8n_language_case' data-case_id='" . $this->id .
               "' data-rule_id='" . ($rule ? $rule->id : '') .
               "' data-case_key='" . str_replace("'", "\'", $element) . "'>" . $value . "</span>";
    }

    public function toArray($keys=array()) {
        $info = parent::toArray(array("id", "keyword", "description", "latin_name", "native_name"));
        $info["rules"] = array();
        foreach($this->rules as $value) {
            array_push($info["rules"], $value->toArray());
        }
        return $info;
    }

}
